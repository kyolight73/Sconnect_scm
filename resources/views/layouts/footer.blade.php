</body>

</html>
@section('js')
    <script src="{{ asset('js/chat.js') }}"></script>
@endsection
</body>
<script>
    $('#insert').on('click', function() {
        $(this).prop('disabled', true);
        $('.spinner-border').show();
    });
</script>
<div class="modal fade" id="modal_setting">
    <form method="POST" action=" {{ route('config.facebook_access_token') }} ">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-color1">Cài đặt chung cho website</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <label>Id bài viết</label>
                    <input type="text" class="form-control " name="group_id" value="" required=""
                        autofocus="" placeholder="Nhập id group"> --}}
                    <label>Facebook Access token</label>
                    <input type="text" class="form-control " name="facebook_access_token" value=""
                        required="" autofocus="" placeholder="Nhập Access token">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary" id="">Lưu
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </form>
    <!-- /.modal-dialog -->
</div>

</html>
