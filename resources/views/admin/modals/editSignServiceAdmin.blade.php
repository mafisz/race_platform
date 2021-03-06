<div class="modal fade" tabindex="-1" role="dialog" id="editSignServiceAdmin" aria-labelledby="editSignServiceAdmin" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white bg-info rounded-top">
                <h5 class="modal-title">Park serwisowy<h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('editSignServiceAdmin') }}">
                    @csrf
                    <input type="hidden" name="round_id" id="service_round_id">
                    <input type="hidden" name="sign_id" id="service_sign_id">

                    <h4 class="text-center my-2">Wybierz uczestników.</h4>
                    <hr>
                    <div id="service_list">

                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                Zapisz
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>