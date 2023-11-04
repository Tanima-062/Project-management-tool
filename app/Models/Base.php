<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Base\Collection;
use App\Manager\RedisManager\RedisManager;
use App\Mixins\ModelMixins\ProtectedFieldMixin;
use App\Mixins\ModelMixins\ProtectedVersionable;
use App\Models\Base\Version;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * @author Fazlul Kabir Shohag <shohag.fks@gmail.com>
 */


class Base extends Model
{
    /**
     * @var Model
     */
    public $model;
    protected $relationDelete = array();

    use SoftDeletes;
    use ProtectedFieldMixin;
    use ProtectedVersionable;

    /**
     * Base constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->tableName = $this->getTable();
    }

    /**
     * @param int $resourcePerPage
     * @param string $orderBy
     * @return JsonResponse
     * @throws \ReflectionException
     */
    function getAll($filter = null, $value = null, $resourcePerPage = 50, $orderBy = 'DESC', $is_role = null, $data_permission = 0, $orderbyField = 'id', $special_query = null)
    {
        $redis_cache = RedisManager::Generic();
        $reflection = new \ReflectionClass($this->model);
        $model_name = $reflection->getShortName();
        $resourcePerPage = $resourcePerPage == 0 ? 10 : $resourcePerPage;
        if($orderbyField != 'id') {
            $orderClause = 'lower('.$this->tableName.'.'.$orderbyField.')'.$orderBy;
            $querySet = $this->model::orderByRaw($orderClause);
        } else {
            $querySet = $this->model::orderBy($this->tableName . '.' . $orderbyField, $orderBy);
        }
        
        if (Schema::hasColumn($this->tableName, 'deleted_at')) {
            $querySet = $querySet->where('deleted_at', Null);
        }

        if(method_exists($this, 'excludeIds') && $special_query == 'is_exclude') {
            $querySet = $querySet->whereNotIn($this->tableName .'.'. $this->excludeField, $this->excludeIds());
        }

        if (!is_null($is_role)) {
            $this->isRoleWise = $is_role;
        }

        if ($this->isRoleWise) {
            $querySet = $this->applyRoleFilter($querySet, $data_permission);
        }

        // with cache table
        if ($this->model->CacheTable) {
            $data = $redis_cache->get($model_name);
            if ($data) {
                $collection = (new Collection($data))->paginate($resourcePerPage);
                return $collection;
            } else {
                return $data;
                $data = $$querySet->get();
                $redis_cache->store($model_name, $data);
                return (new Collection($data))->paginate($resourcePerPage);
            }
        } else {
            // without cache
            if ($filter && $value) {
                $querySet = $querySet->where($filter, $value);
            } else if ($filter) {
                $querySet = $this->appliedMullipleFilter($querySet, $filter);
            }
        }
        // others responsibility data
        if ($this->is_doa) {
            $querySet = $this->responsibilityData($querySet);
        }

        if($special_query) {
            $query_keys = explode(':', $special_query);
            if($query_keys[0] == 'unique') {
                if(!$filter) {
                    $querySet = $this->joinTable($querySet);
                }
                $key = $this->getTableNameFromRelationKey($query_keys[1]);
                $querySet = $querySet->groupBy($key);
            }
        }

        if (method_exists($this, 'selectFields')) {
            $querySet->select($this->selectQueryField());
        }

        return $querySet->paginate($resourcePerPage);
    }


    function getResourceById($request, $id, $filter = null, $orderBy = null, $order_by_field = null)
    {
        $orderbyField =  $order_by_field ? $order_by_field : 'id';
        $orderBy = $orderBy ? $orderBy : 'DESC';
        if ($id == 'all') {
            $querySet = $this->model::where('deleted_at', NULL);
            if ($filter) {
                $querySet = $this->appliedMullipleFilter($querySet, $filter);
            }
            $querySet = $querySet->orderBy($orderbyField, $orderBy);
            $resource = $querySet->first();
        } else if ($request->field) {
            $field = $request->field;
            $querySet = $this->model::where('deleted_at', NULL)->where($field, $id);
            if ($filter) {
                $querySet = $this->appliedMullipleFilter($querySet, $filter);
            }
            $querySet = $querySet->orderBy($orderbyField, $orderBy);
            $resource = $querySet->first();
        } else {
            $resource = $this->model::where('deleted_at', NULL)->find($id);
        }

        if (empty($resource)) return response()->json(['errors' => 'Resource not found', 'status' => 404], 200);

        if ($request->version) {
            $version = Version::where('versionable_id', $resource->id)
                ->where('version', $request->version)
                ->where('versionable_type', $this->getVersionableClassName())->first();
            if (!$version) {
                return response()->json(['errors' => 'Resource not found', 'status' => 404], 200);
            } else {
                $versionableDate = json_decode($version->model_data, true);
                foreach ($versionableDate as $field => $value) {
                    $resource->$field = $value;
                }
                return $resource;
            }
        }

        return $resource;
    }
    /**
     * @param $id
     * @return JsonResponse
     */
    function deleteResource($request, $id)
    {
        $resource = $this->model::find($id);

        if (empty($resource)) return response()->json(['message' => 'Resource not found.'], 404);

        $resource->deleted_at = Carbon::now();
        $resource->deleted_by = Auth::user()->id;
        $resource->save();

        // Delete Log Entity
        $logEntity = json_encode($resource);
        $url = $request->url();
        $modelName = get_class($this->model);
        Log::info($logEntity, ['model' => $modelName, 'type' => 'delete', 'url' => $url]);

        //related foreign key deleted
        try {
            if (method_exists($this->model, 'forignkeyDelete')) {
                $this->relationDelete = $this->forignkeyDelete();
            }
            foreach ($this->relationDelete as $relation) {
                $field =  $relation->field;
                $relation->model::where('id', $resource->$field)->update(['deleted_at' => Carbon::now()]);
            }
        } catch (Exception $e) {
        }

        return response()->json(['message' => 'Resource delete successfully.'], 200);
    }

    function searchResource($searchBy)
    {
        $resource = $this->model::where('name', 'like', "%{$searchBy}%")->get();

        if (empty($resource)) return response()->json(['message' => 'Resource not found.']);
        return $resource;
    }

    public function getByColumnName($request, $column)
    {
        return  $this->model::where($column, $request)->orderBy('id', 'desc')->get();
    }
}
