@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'basic'])

@section('title')
    Settings
@endsection

@section('content-header')
    <h1>面板设置<small>按你喜欢设置这个面板.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理员</a></li>
        <li class="active">设置</li>
    </ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">面板设置</h3>
                </div>
                <form action="{{ route('admin.settings') }}" method="POST">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">公司名称</label>
                                <div>
                                    <input type="text" class="form-control" name="app:name" value="{{ old('app:name', config('app.name')) }}" />
                                    <p class="text-muted"><small>这是整个面板和发送给客户的电子邮件中使用的名称。</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Google Analytics</label>
                                <div>
                                    <input type="text" class="form-control" name="app:analytics" value="{{ old('app:analytics', config('app.analytics')) }}" />
                                    <p class="text-muted"><small>这是您的 Google Analytics 跟踪 ID, 例如. UA-123723645-2</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">强制使用双因素 2FA 身份验证</label>
                                <div>
                                    <div class="btn-group" data-toggle="buttons">
                                        @php
                                            $level = old('pterodactyl:auth:2fa_required', config('pterodactyl.auth.2fa_required'));
                                        @endphp
                                        <label class="btn btn-primary @if ($level == 0) active @endif">
                                            <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="0" @if ($level == 0) checked @endif> 不需要使用
                                        </label>
                                        <label class="btn btn-primary @if ($level == 1) active @endif">
                                            <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="1" @if ($level == 1) checked @endif> 仅管理员
                                        </label>
                                        <label class="btn btn-primary @if ($level == 2) active @endif">
                                            <input type="radio" name="pterodactyl:auth:2fa_required" autocomplete="off" value="2" @if ($level == 2) checked @endif> 所有用户
                                        </label>
                                    </div>
                                    <p class="text-muted"><small>如果启用，任何落入所选分组的帐户都需要启用双因素 2FA 身份验证才能使用面板。</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">默认语言（DANE这家伙还没做好）</label>
                                <div>
                                    <select name="app:locale" class="form-control">
                                        @foreach($languages as $key => $value)
                                            <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted"><small>渲染 UI 组件时使用的默认语言.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button type="submit" name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
