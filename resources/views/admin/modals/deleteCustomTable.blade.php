<div class="modal fade" tabindex="-1" role="dialog" id="deleteCustomTable" aria-labelledby="deleteCustomTable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-white bg-info rounded-top">
                <h5 class="modal-title">Usuń tabelę</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('deleteCustomTable') }}">
                    @csrf
                    <input type="hidden" name="id" id="delete_id2">
                    
                    <p class="h5 text-center">Czy jesteś pewien, że chcesz usunąć wybraną tabelę?</p>

                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-4">
                            <button type="submit" class="btn btn-danger btn-block">
                                Usuń tabelę
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>