@extends('project.layout.app')
@section('style')
    <style>
        textarea::placeholder {
            size: 20px;
            color: red;
        }
    </style>
@endsection
@section('main')
    {{-- <form action="/file-upload" class="dropzone" id="dropzone-area" enctype="multipart/form-data">
</form> --}}
    <section id="about" class="about">
        {{-- <embed src="https://www.codeur.com/tuto/wp-content/uploads/2022/02/pdf-test.pdf" width="800" height="500" type="application/pdf"/> --}}

        <div class="container" data-aos="fade-up">
            {{-- <div class="card shadow" style="width: 10rem;">
                <div class="card-body">
                    <h3 class="card-title text-center"><i class="fa-solid fa-spell-check"></i> {{ __('Plagiarism of a text') }}</h3>
                </div>
            </div> --}}

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                        role="tab" aria-controls="home" aria-selected="true">{{ __('Plagiarism of a text') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#plag_doc" type="button"
                        role="tab" aria-controls="plag_doc"
                        aria-selected="false">{{ __('Plagiarism of a document') }}</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="card">
                        <div class="card-body">


                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('cosine.calcaulateText') }}" method="POST" id="detection_text">
                                        @csrf
                                        <input type="hidden" name="link_test" id="link_test"
                                            value="{{ route('cosine.calcaulateText') }}">
                                        <textarea required name="texts" id="contenu" rows="15" class="form-control"
                                            placeholder="{{ __('Write or paste your text here') }}"></textarea>
                                        <div class="text-center">
                                            <br>
                                            {{-- <input class="btn btn-primary" type="submit" value="{{ __('Detection') }}"> --}}
                                            <button class="btn btn-primary">{{ __('Detection') }}</button>

                                        </div>
                                    </form>
                                    {{-- Une base de données est une collection organisée d'informations structurées, généralement stockées électroniquement dans un système informatique. Une base de données est généralement contrôlée par un système de gestion de base de données (DBMS). --}}
                                </div>

                                <div class="col-md-6">
                                    <ol class="Message_scan">
                                    </ol>
                                    <span></span>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="plag_doc" class="plag_doc" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <div class="row">

                        <div class="col-12">
                            {{-- {{ route('cosine.calculateSimilarity') }} --}}
                            <span class="d-none " id="link_doc">{{ route('cosine.calculateSimilarity') }}</span>
                            <span class="d-none " id="link_file">{{ route('cosine.read') }}</span>
                            <span class="d-none " id="link_selected">{{ route('cosine.selected_sentences') }}</span>
                            <span class="d-none " id="link_search">{{ route('cosine.google_search') }}</span>
                            <span class="d-none " id="link_scrap">{{ route('cosine.scrapping_link') }}</span>
                            <span class="d-none " id="link_db">{{ route('cosine.searchDB') }}</span>
                            <form action="" method="GET" enctype="multipart/form-data" id="textPlagairForm"
                                novalidate>
                                @csrf
                                <div class="iconS_dis"> <i class="text-success fa-solid fa-file-pdf"></i>&ensp; <i
                                        class="text-success fa-solid fa-file-word"></i>&ensp; <i
                                        class="text-success fa-solid fa-file-lines"></i> </div>
                                <div  id="zone_file">
                                    <input required type="file" id="file" class="file" name="file"
                                        is="drop-files" label="{{ __('Drag & Drop a file') }}"
                                        help="{{ __('PDF or Word(.docx) or .txt') }}"
                                        accept="application/pdf,application/msword,
                                        application/vnd.openxmlformats-officedocument.wordprocessingml.document, .txt">
                                </div>
                                    <div id="zone_text" class="d-none">
                                        <textarea name="file_content" id="file_content" rows="100" class="form-control"> </textarea>
                                        <span onkeyup="actualiseNombreMots()" class="text-secondary"
                                            id="word_number"></span>

                                    </div>


                                {{-- <textarea name="file_content" id="file_content" rows="100" class="form-control"></textarea> --}}

                                {{-- accept="application/pdf,application/msword,
                                    application/vnd.openxmlformats-officedocument.wordprocessingml.document" --}}
                                <br>
                                <div class="text-center" id="btn_load">
                                    <button class="valid submits btn btn-primary">Charger le document</button>
                                    {{-- <input name="submit" class="submit btn btn-primary" type="button" value="{{ __('Detection') }}" /> --}}
                                </div>
                            </form>

                        </div>
                        <div class="col-12">
                            <br> <br>
                            <ul class="message_doc">

                            </ul>
                        </div>
                        {{-- <a href="javascript:void(0)" class="btn btn-primary search_link" >Recherche des liens</a> --}}
                    </div>
                </div>

            </div>
        </div>

        </div>
    </section>
@endsection
@section('modals')
    <div class="modal fade" id="PatientModal">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-body text-center d-flex  justify-content-center align-items-center">
                    <div>
                        <div class="spinner-border text-success" role="status">
                        </div><br>
                        <h3 class="text-secondary" id="simple_mes"></h3>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="ListContentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light text-center d-flex  justify-content-center align-items-center">
                    <h5 id="title_modal" class="modal-title h3 text-center text-lg-start text-center text-secondary"></h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>

                <div class="modal-body">
                    <ul id="body_detect">

                    </ul>
                </div>
                <div id="modal_footer" class="modal-footer">
                    {{-- <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary">Recherche des liens</button> --}}
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/validText.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/test.js') }}"></script> --}}
@endsection
