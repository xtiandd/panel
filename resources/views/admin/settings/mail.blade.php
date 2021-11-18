@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'mail'])

@section('title')
    Mail Settings
@endsection

@section('content-header')
    <h1>邮件服务器设置<small>配置翼龙面板应如何发送电子邮件。</small></h1>
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
                    <h3 class="box-title">邮件服务器设置</h3>
                </div>
                @if($disabled)
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="alert alert-info no-margin-bottom">
                                    此接口仅限于使用 SMTP 作为邮件驱动程序的实例. 请使用 <code>php artisan p:environment:mail</code> 命令来更新邮件服务器设置, 或使用 <code>MAIL_DRIVER=smtp</code> 于您的.env环境配置文件.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">SMTP 服务器域名</label>
                                    <div>
                                        <input required type="text" class="form-control" name="mail:host" value="{{ old('mail:host', config('mail.host')) }}" />
                                        <p class="text-muted small">输入邮件 SMTP 服务器地址.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="control-label">SMTP 服务器端口</label>
                                    <div>
                                        <input required type="number" class="form-control" name="mail:port" value="{{ old('mail:port', config('mail.port')) }}" />
                                        <p class="text-muted small">输入邮件 SMTP 服务器端口.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">加密方式</label>
                                    <div>
                                        @php
                                            $encryption = old('mail:encryption', config('mail.encryption'));
                                        @endphp
                                        <select name="mail:encryption" class="form-control">
                                            <option value="" @if($encryption === '') selected @endif>无加密</option>
                                            <option value="tls" @if($encryption === 'tls') selected @endif>Transport Layer Security (TLS)</option>
                                            <option value="ssl" @if($encryption === 'ssl') selected @endif>Secure Sockets Layer (SSL)</option>
                                        </select>
                                        <p class="text-muted small">选择发送邮件时使用的加密类型.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">用户名 <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="form-control" name="mail:username" value="{{ old('mail:username', config('mail.username')) }}" />
                                        <p class="text-muted small">连接到 SMTP 服务器时使用的用户名.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">密码 <span class="field-optional"></span></label>
                                    <div>
                                        <input type="password" class="form-control" name="mail:password"/>
                                        <p class="text-muted small">与 SMTP 用户名一起使用的密码。 留空以继续使用现有密码。 要将密码设置为空值，请输入 <code>!e</code> 于此.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <hr />
                                <div class="form-group col-md-6">
                                    <label class="control-label">发送自</label>
                                    <div>
                                        <input required type="email" class="form-control" name="mail:from:address" value="{{ old('mail:from:address', config('mail.from.address')) }}" />
                                        <p class="text-muted small">输入所有外发电子邮件的来源电子邮件地址.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">发件人名称 <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="form-control" name="mail:from:name" value="{{ old('mail:from:name', config('mail.from.name')) }}" />
                                        <p class="text-muted small">电子邮件发件人使用的的名称.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            {{ csrf_field() }}
                            <div class="pull-right">
                                <button type="button" id="testButton" class="btn btn-sm btn-success">测试</button>
                                <button type="button" id="saveButton" class="btn btn-sm btn-primary">保存</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent

    <script>
        function saveSettings() {
            return $.ajax({
                method: 'PATCH',
                url: '/admin/settings/mail',
                contentType: 'application/json',
                data: JSON.stringify({
                    'mail:host': $('input[name="mail:host"]').val(),
                    'mail:port': $('input[name="mail:port"]').val(),
                    'mail:encryption': $('select[name="mail:encryption"]').val(),
                    'mail:username': $('input[name="mail:username"]').val(),
                    'mail:password': $('input[name="mail:password"]').val(),
                    'mail:from:address': $('input[name="mail:from:address"]').val(),
                    'mail:from:name': $('input[name="mail:from:name"]').val()
                }),
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
            }).fail(function (jqXHR) {
                showErrorDialog(jqXHR, 'save');
            });
        }

        function testSettings() {
            swal({
                type: 'info',
                title: '测试邮件服务器配置',
                text: '点击 “测试” 按钮开始测试。',
                showCancelButton: true,
                confirmButtonText: 'Test',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'POST',
                    url: '/admin/settings/mail/test',
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                }).fail(function (jqXHR) {
                    showErrorDialog(jqXHR, 'test');
                }).done(function () {
                    swal({
                        title: '成功',
                        text: '测试信息已成功发送.',
                        type: 'success'
                    });
                });
            });
        }

        function saveAndTestSettings() {
            saveSettings().done(testSettings);
        }

        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }

            swal({
                title: 'Whoops!',
                text: 'An error occurred while attempting to ' + verb + ' mail settings: ' + errorText,
                type: 'error'
            });
        }

        $(document).ready(function () {
            $('#testButton').on('click', saveAndTestSettings);
            $('#saveButton').on('click', function () {
                saveSettings().done(function () {
                    swal({
                        title: 'Success',
                        text: 'Mail settings have been updated successfully and the queue worker was restarted to apply these changes.',
                        type: 'success'
                    });
                });
            });
        });
    </script>
@endsection
