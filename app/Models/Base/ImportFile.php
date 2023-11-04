<?php

namespace App\Models\Base;

use App\Models\Base\BaseModel;

/**
 * @author Fazlul Kabir Shohag <shohag.fks@gmail.com>
 */

class ImportFile extends BaseModel
{
    protected $table = 'import_files';

    function __construct()
    {
        parent::__construct($this);
    }

    public function serializerFields()
    {
        return ['id', 'url', 'company_id', 'name', 'extension', 'file_path', 'description', 'created_by', 'updated_by'];
    }

    static public function postserializerFields()
    {
        return ['company_id', 'name', 'extension', 'file_path', 'description', 'created_by', 'updated_by'];
    }

    static public function fieldsValidator()
    {
        return [
            'name' => 'required',
            'file_path' => 'required'
        ];
    }
}
