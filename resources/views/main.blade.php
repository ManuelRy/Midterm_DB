@extends('master.master')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Blockchain Explorer</title>
        <style>
            table,
            td,
            th {
                border: 1px solid black;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <h2 class="text-dark">Home</h2>
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body bg-gray-100">
                            <div class="row mb-2">
                                <div class="col-md-9">
                                    <button class="btn btn-dark" onclick="toggleChainValidity()" data-toggle="modal"
                                        data-target="#exampleModal">Verify Blockchain</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <h5>Current Balance: {{ $balance }}</h5>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Hash</th>
                                                <th>Previous Hash</th>
                                                <th>Timestamp</th>
                                                <th>Nonce</th>
                                                <th>Pending Transactions</th>
                                                <th>Add Transaction</th>
                                                <th>Mine</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($blocks))
                                                @foreach ($blocks as $index => $block)
                                                    <tr>
                                                        <td>{{ $block->id }}</td>
                                                        <td>{{ $block->hash }}</td>
                                                        <td>{{ $block->preHash }}</td>
                                                        <td>{{ $block->timestamp }}</td>
                                                        <td>{{ $block->nonce }}</td>
                                                        <td>{{ $trans->where('block_id', $block->id)->count() }}</td>
                                                        <td>
                                                            @if ($index + 1 == count($blocks))
                                                                <a href="{{ url('/addTransaction/' . $block->id) }}"><button
                                                                        type="button" class="btn btn-dark">Add</button></a>
                                                            @else
                                                                <p class="text-primary">Added</p>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($index + 1 == count($blocks))
                                                                <a href="{{ url('/mine/' . $block->id) }}"><button
                                                                        type="button"
                                                                        class="btn btn-dark">Mine</button></a>
                                                            @else
                                                                <p class="text-danger">Mined</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Verify Blockchain</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5 id="blockchain-status" class="text-success">

                            </h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function toggleChainValidity() {
                    var isChainValid = '{{ $isChainValid }}';
                    isChainValid = isChainValid === 'true' ? 'false' : 'true';
                    document.getElementById('blockchain-status').innerText = isChainValid === 'true' ? 'Blockchain is valid' :
                        'Blockchain is invalid';
                }
            </script>
        </div>
    @stop
</body>

</html>
