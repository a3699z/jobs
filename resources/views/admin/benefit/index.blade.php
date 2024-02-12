@extends('admin.layouts.app')
@section('title')
    {{ __('benefit_list') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title line-height-36">{{ __('benefit_list') }}
                            ({{ $benefits ? $benefits->total() : '0' }})</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>{{ __('name') }}</th>
                                    @if (userCan('benefits.update') || userCan('benefits.delete'))
                                        <th width="10%">{{ __('action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($benefits as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if (userCan('benefits.update'))
                                                <a href="{{ route('benefit.edit', $item->slug) }}" class="btn bg-info"><i
                                                        class="fas fa-edit"></i></a>
                                            @endif
                                            @if (userCan('benefits.delete'))
                                                <form action="{{ route('benefit.destroy', $item->slug) }}" method="POST"
                                                    class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button
                                                        onclick="return confirm('{{ __('are_you_sure_you_want_to_delete_this_item') }}');"
                                                        class="btn bg-danger"><i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            {{ __('no_data_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="col-3 m-auto">
                            {{ $benefits->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @if (!empty($benefit) && userCan('benefits.update'))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title line-height-36">{{ __('edit') }}</h3>
                            <a href="{{ route('benefit.index') }}"
                                class="btn bg-primary float-right d-flex align-items-center justify-content-center"><i
                                    class="fas fa-plus mr-1"></i>{{ __('create') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="pt-3 pb-4">
                                <form class="form-horizontal" action="{{ route('benefit.update', $benefit->slug) }}"
                                    method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group row">
                                        <x-forms.label name="name" for="name" :required="true"
                                            class="col-md-3 col-form-label" />
                                        <div class="col-md-9">
                                            <input id="name" type="text" name="name"
                                                value="{{ old('name', $benefit->name) }}" placeholder="{{ __('name') }}"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row m-auto">
                                        <button type="submit" class="btn btn-success offset-md-3">
                                            <i class="fas fa-sync mr-1"></i>
                                            {{ __('update') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @if (empty($benefit) && userCan('benefits.create'))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title line-height-36">{{ __('create') }}</h3>
                        </div>
                        <div class="card-body">
                            @if (userCan('benefits.create'))
                                <form class="form-horizontal" action="{{ route('benefit.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <x-forms.label name="name" for="name" :required="true"
                                            class="col-md-3 col-form-label" />
                                        <div class="col-md-9">
                                            <input id="name" type="text" name="name"
                                                placeholder="{{ __('name') }}" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success offset-md-3">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ __('save') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p>{{ __('dont_have_permission') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
