<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Tag;

class TagController extends Controller
{

    protected $fields = [
        'tag' => '',
        'title' => '',
        'subtitle' => '',
        'meta_description' => '',
        'page_image' => '',
        'layout' => 'blog.layouts.index',
        'reverse_direction' => 0,
    ];

    /**
     * Display a listing of all Tags.
     *
     * @return Response
     */
    public function index()
    {
        $tags = Tag::all();

        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     *
     * @return Response
     */
    public function create()
    {
        $formData = $this->prepareFormFields();

        return view('admin.tag.create', $formData);
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  CreateTagRequest $request
     * @return Response
     */
    public function store(CreateTagRequest $request)
    {
        $tag = Tag::createNewTag($request->all());

        return redirect()
            ->route('admin.tag.index')
            ->withSuccess("The tag '$tag->tag' was created.");
    }

    /**
     * Show the form for editing the specified tag.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $formData = $this->prepareFormFields($id);

        return view('admin.tag.edit', $formData);
    }

    /**
     * Update the specified tag in storage.
     *
     * @param  UpdateTagRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);

        foreach (array_keys(array_except($this->fields, ['tag'])) as $field) {
            $tag->$field = $request->get($field);
        }
        $tag->save();

        return redirect()
            ->route('admin.tag.edit', [$tag])
            ->withSuccess("Changes saved.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()
            ->route('admin.tag.index')
            ->withSuccess("The '$tag->tag' tag has been deleted.");
    }

    /**
     * Prepare default values or the form create and edit forms
     *
     * @param null $id
     * @return array
     */
    public function prepareFormFields($id = null)
    {
        if(!$id){
            $formFields = [];
            foreach ($this->fields as $field => $default) {
                $formFields[$field] = old($field, $default);
            }

            return $formFields;
        }

        $tag = Tag::findOrFail($id);
        $formFields = ['id' => $id];

        foreach (array_keys($this->fields) as $field) {
            $formFields[$field] = old($field, $tag->$field);
        }

        return $formFields;
    }
}
