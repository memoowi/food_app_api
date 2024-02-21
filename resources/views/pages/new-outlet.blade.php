@extends('layout')

@section('title')
    New Outlet
@endsection

@section('content')
    <h1>New Outlet</h1>
    <form action="{{ route('outlets.store') }}" method="post">
        @csrf
        <label for="name">Outlet Name</label>
        <input type="text" name="name" id="name"><br/>
        <label for="address">Address</label>
        <input type="text" name="address" id="address"><br/>
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone"><br/>
        <label for="open_time">Open Time</label>
        <input type="time" name="open_time" id="open_time"><br/>
        <label for="close_time">Close Time</label>
        <input type="time" name="close_time" id="close_time"><br/>
        <button type="submit">Create</button>
    </form>
@endSection