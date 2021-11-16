{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    Administration
@endsection

@section('content-header')
    <h1>管理概况<small>系统概况.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">管理员</a></li>
        <li class="active">主页</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box
            @if($version->isLatestPanel())
                box-success
            @else
                box-danger
            @endif
        ">
            <div class="box-header with-border">
                <h3 class="box-title">系统信息</h3>
            </div>
            <div class="box-body">
                @if ($version->isLatestPanel())
                    你正在运行的翼龙面板版本  <code>{{ config('app.version') }}</code>. 其为最新版本!
                @else
                    面板版本 <strong>并不是最新的!</strong> 其最新版本为 <a href="https://github.com/Pterodactyl/Panel/releases/v{{ $version->getPanel() }}" target="_blank"><code>{{ $version->getPanel() }}</code></a> 而你正在使用的版本为 <code>{{ config('app.version') }}</code>.
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDiscord() }}"><button class="btn btn-warning" style="width:100%;"><i class="fa fa-fw fa-support"></i> 获取帮助 <small>(通过 Discord)</small></button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://pterodactyl.io"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-link"></i> 文档</button></a>
    </div>
    <div class="clearfix visible-xs-block">&nbsp;</div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://github.com/pterodactyl/panel"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-support"></i> Github</button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDonations() }}"><button class="btn btn-success" style="width:100%;"><i class="fa fa-fw fa-money"></i> 支持此项目</button></a>
    </div>
</div>
@endsection
