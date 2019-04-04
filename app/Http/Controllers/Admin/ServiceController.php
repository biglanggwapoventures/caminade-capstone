<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toast;

class ServiceController extends CRUDController
{
    protected $filterFields = [
        'name' => ['name', 'like', '%_%'],
        'status' => ['service_status', '='],
    ];

    public function __construct(Service $model, Request $request)
    {
        $this->middleware('role:admin', ['except' => ['index']]);
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'name' => ['required', Rule::unique($model->getTable())],
                'description' => ['present'],
                'duration' => ['required', 'integer'],
                'service_status' => ['sometimes', 'nullable', Rule::in(['active', 'inactive'])],
                'price' => ['required', 'numeric'],
            ],
            'update' => [
                'name' => ['required', Rule::unique($model->getTable())->ignore($request->route('service'))],
                'description' => ['present'],
                'duration' => ['required', 'integer'],
                'price' => ['required', 'numeric'],
                'service_status' => ['required', Rule::in(['active', 'inactive'])],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        collect($this->filterFields)->each(function ($value, $key) use ($query) {
            if ($filter = request()->{$key}) {
                list($column, $operand) = $value;
                $filter = isset($value[2]) ? str_replace('_', $filter, $value[2]) : $filter;
                $query->where($column, $operand, $filter);
            }
        });
    }

    public function afterStore($model)
    {
        Toast::success('New service has been added!');
    }

    public function afterUpdate($model)
    {
        Toast::success('Service has been successfully updated!');
    }

}
