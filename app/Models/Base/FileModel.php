<?php

namespace App\Models\Base;

use App\Models\Base\BaseModel;

/**
 * @author Fazlul Kabir Shohag <shohag.fks@gmail.com>
 */

class FileModel extends BaseModel
{

    protected $table = 'files';

    function __construct()
    {
        parent::__construct($this);
    }

    public function serializerFields()
    {
        return ['id', 'company_id', 'name', 'related_id', 'extension', 'path', 'description', 'created_by', 'updated_by'];
    }

    static public function postserializerFields()
    {
        return ['company_id', 'name', 'model', 'related_id', 'extension', 'path', 'description', 'created_by', 'updated_by'];
    }

    static public function fieldsValidator()
    {
        return [
            'name' => 'required',
            'path' => 'required'
        ];
    }
}
