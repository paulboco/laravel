@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Property Index</div>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>{{ $property->property_street_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <pre>
                        {{ var_export($properties, true)}}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
