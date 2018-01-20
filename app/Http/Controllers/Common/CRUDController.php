<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use MyHelper;

class CRUDController extends Controller
{

    /**
     * The base resource model
     *
     * @var Model
     */
    protected $resourceModel = null;

    /**
     * The base resource model's related model
     *
     * @var mixed
     */
    protected $relatedModel = null;

    /**
     * The validationa rules to use during storing/updating the resource
     *
     * @var array
     */
    protected $validationRules = [];

    /**
     * The validated input
     *
     * @var array
     */
    protected $validatedInput = [];

    /**
     * The base dir for the route views
     *
     * @var string
     */
    protected $viewBaseDir = null;

    /**
     * The data passed to the rendered view
     *
     * @var array
     */
    protected $viewData = [];

    /**
     * The location of the views
     *
     * @var array
     */
    protected $viewFiles = [
        'index' => 'index',
        'create' => 'manage',
        'edit' => 'manage',
        'show' => 'show',
    ];

    public function __construct()
    {
        $this->viewBaseDir = is_null($this->viewBaseDir) ? $this->getBaseDir() : $this->viewBaseDir;
    }

    /**
     * Get the view base dir for the route
     *
     * @return string
     */
    private function getBaseDir()
    {
        $strip = 'Controller';
        $lastNamespace = 'Controllers\\';
        $fullNamespace = get_class($this);

        if ($pos = strrpos($fullNamespace, $lastNamespace)) {
            $className = substr($fullNamespace, $pos + strlen($lastNamespace));
        } else {
            $className = $pos;
        }

        $stripped = str_replace($strip, '', $className);
        $segments = array_map(function ($item) {
            return implode('-', preg_split('/(?<=[a-z])(?=[A-Z])|(?=[A-Z][a-z])/', $item, -1, PREG_SPLIT_NO_EMPTY));
        }, explode('\\', $stripped));

        return strtolower(implode('.', $segments));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->resourceModel::query();

        $this->beforeIndex($query);

        $query->fieldsForMasterList();

        $this->viewData['resourceList'] = $query->get();

        return view(
            "{$this->viewBaseDir}.{$this->viewFiles['index']}",
            $this->viewData
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->beforeCreate();

        $this->viewData['resourceData'] = new $this->resourceModel;

        return view(
            "{$this->viewBaseDir}.{$this->viewFiles['create']}",
            $this->viewData
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resource = null;

        DB::transaction(function () use (&$request, &$resource) {

            $this->validatedInput = $request->validate($this->validationRules['store']);

            $this->beforeStore();

            $resource = $this->createParentModel();

            if ($this->hasInputRelation()) {
                $this->createRelations($resource);
            }

            $this->afterStore($resource);

        }, 3);

        if ($request->ajax()) {
            return response()->json([
                'result' => true,
                'data' => [
                    'id' => $resource->id,
                    'location' => MyHelper::resource('show', ['id' => $resource->id]),
                ],
            ]);
        }

        return redirect(MyHelper::route('index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->resourceModel::find($id);

        $this->beforeShow($model);

        if (!is_null($this->relatedModel) && !$model->relationLoaded($this->relatedModel)) {
            $model->load($this->relatedModel);
        }

        $this->viewData['resourceData'] = $model;

        return view(
            "{$this->viewBaseDir}.{$this->viewFiles['show']}",
            $this->viewData
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = $this->resourceModel::find($id);

        if (!is_null($this->relatedModel)) {
            $model->load($this->relatedModel);
        }

        $this->beforeEdit($model);

        $this->viewData['resourceData'] = $model;

        return view(
            "{$this->viewBaseDir}.{$this->viewFiles['edit']}",
            $this->viewData
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $resource = null;

        DB::transaction(function () use (&$request, &$resource, $id) {

            $this->validatedInput = $request->validate($this->validationRules['update']);

            $this->beforeUpdate();

            $resource = $this->resourceModel::find($id);

            $this->updateParentModel($resource);

            if ($this->hasInputRelation()) {
                $this->updateParentRelations($resource);
            }

            $this->afterUpdate($resource);

        }, 3);

        if ($request->ajax()) {
            return response()->json([
                'result' => true,
                'data' => [
                    'id' => $resource->id,
                    'location' => MyHelper::resource('show', ['id' => $resource->id]),
                ],
            ]);
        }

        return redirect(MyHelper::route('index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->resourceModel::find($id)->delete();

        if ($request->ajax()) {
            return response()->json(['result' => true]);
        }

        return redirect(MyHelper::route('index'));
    }

    private function getParentInput()
    {
        return $this->validatedInput['parent'] ?? $this->validatedInput;
    }

    /**
     * Creates the parent model
     *
     * @return Model
     */
    private function createParentModel()
    {
        $resourceModelInput = $this->getParentInput();
        return $this->resourceModel::create($resourceModelInput);
    }

    /**
     * Determine if the input from user includes a relation
     *
     * @return boolean
     */
    private function hasInputRelation()
    {
        return !is_null($this->relatedModel) && isset($this->validatedInput['child']);
    }

    private function isHasManyRelation($key = 'child')
    {
        return count($this->validatedInput[$key]) !== count($this->validatedInput[$key], COUNT_RECURSIVE);
    }

    /**
     * Creates the parent model's relations
     *
     * @param  Model $model The parent model
     */
    protected function createRelations($model, $key = 'child', $relationModelName = null)
    {
        $relation = $relationModelName ?? $this->relatedModel;

        $child = $this->validatedInput[$key];

        if ($this->isHasManyRelation($key)) {
            $model->{$relation}()->createMany($child);
        } else {
            $model->{$relation}()->create($child);
        }
    }

    private function updateParentModel($model)
    {
        $resourceModelInput = $this->getParentInput();
        $model->fill($resourceModelInput);
        $model->save();
    }

    protected function updateParentRelations($model, $key = 'child', $relationModelName = null)
    {
        $relationName = $relationModelName ?? $this->relatedModel;

        $child = $this->validatedInput[$key];

        if (!$this->isHasManyRelation($key)) {
            $relation = $model->{$relationName}();
            $relation->fill($child);
            $relation->save();
            return true;
        }

        $new = [];
        $updated = [];

        foreach ($child as $row) {
            if (empty(array_filter(array_values($row)))) {
                continue;
            }

            if (!isset($row['id'])) {
                $new[] = $row;
            } else {
                $updated[$row['id']] = array_except($row, ['id']);
            }
        }

        $children = $model->{$relationName};

        $children->each(function ($item) use ($updated) {
            if (in_array($item->id, array_keys($updated))) {
                $item->fill($updated[$item->id]);
                $item->save();
            } else {
                $item->delete();
            }
        });

        if (!empty($new)) {
            $model->{$relationName}()->createMany($new);
        }

    }

    public function beforeIndex($query)
    {

    }

    public function beforeShow($model)
    {

    }

    public function beforeCreate()
    {

    }

    public function beforeStore()
    {

    }

    public function afterStore($model)
    {

    }

    public function beforeEdit($model)
    {

    }

    public function beforeUpdate()
    {

    }

    public function afterUpdate($model)
    {

    }
}
