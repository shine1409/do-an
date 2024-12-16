<?php

namespace App\Http\Controllers\Admin\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\Table\CreateTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $table = Table::paginate(5);
        return view('admin.table.list', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        return view('admin.table.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTableRequest $request)
    {
        Table::createNewTable($request->validated());
        flash()->success('Thêm thành công.');
        return redirect()->route('table.list');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        return view('admin.table.edit', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, $id)
    {
        $table = Table::findOrFail($id);
        $table->updateTable($request->validated());
        flash()->success('Cập nhật thành công.');
        return redirect()->route('table.list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        flash()->success('Xóa thành công.');
        return redirect()->route('table.list');
    }
}
