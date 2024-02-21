@extends('layout')

@section('title')
    New Menu
@endsection

@section('content')
    <h1>New Menu</h1>
    <form action="{{ route('outlets.storeMenu', $outlet->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="outlet_id" value="{{ $outlet->id }}">
        <label for="name">Menu Name</label>
        <input type="text" name="name" id="name"><br />
        <label for="image">Image</label>
        <input type="file" name="image" id="image"><br />
        <label for="type">Type</label>
        <select name="type" id="type">
            <option value="" disabled selected>--SELECT TYPE--</option>
            <option value="food">Food</option>
            <option value="beverage">Beverage</option>
        </select><br />
        <label for="price">Price</label>
        <input type="number" name="price" id="price"><br />
        <button type="submit">Create</button>
    </form>
@endSection
