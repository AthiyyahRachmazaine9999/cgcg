                        <div class="uploads_files files_{{$num}}">
                            <div class=""><strong>Dokumen Ke-{{$num}}</strong>
                            </div>
                            <br>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nama Dokumen</label>
                                <div class="col-lg-7">
                                    <input type="text" name="doc_name[]" placeholder="Nama Dokumen" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Upload</label>
                                <div class="col-lg-7">
                                    <input type="file" name="files[]" class="file-input form-control" required>
                                </div>
                                <button type="button" onClick="remove_uploads({{$num}})"
                                    class="btn btn-outline-danger btn-icon rounded-round legitRipple">
                                    <b><i class="fas fa-trash"></i></b></button>
                            </div>
                        </div>
                        <script src="{{ asset('ctrl/upload/upload_file.js?v=').rand() }}" type="text/javascript">
                        </script>