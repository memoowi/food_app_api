@extends('layout')

@section('title')
    Home
@endsection

@section('content')
    <h1>Outlets Data</h1>
    <div>
        <a href="{{ route('outlets.create') }}">
            <button>Create New Outlet</button>
        </a>
    </div>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Outlet Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Code</th>
            <th>Open Time</th>
            <th>Close Time</th>
            <th>Action</th>
        </tr>
        @foreach ($outlets as $outlet)
            <tr>
                <td>{{ $outlet->id }}</td>
                <td>{{ $outlet->name }}</td>
                <td>{{ $outlet->address }}</td>
                <td>{{ $outlet->phone }}</td>
                <td>{{ $outlet->code }}</td>
                <td>{{ $outlet->open_time }}</td>
                <td>{{ $outlet->close_time }}</td>
                <td>
                    <form action="{{ route('outlets.updateCode', $outlet->id) }}" method="post">
                        @csrf
                        <button type="submit">Update Code</button>
                    </form>
                    <a href="{{ route('outlets.show', $outlet->id) }}">
                        <button>Show</button>
                    </a>
                </td>
            </tr>
        @endforeach
        @if (count($outlets) == 0)
            <tr>
                <td colspan="8">No Data</td>
            </tr>
        @endif
    </table>
@endsection
