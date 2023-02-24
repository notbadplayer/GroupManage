<div class="d-flex bd-highlight">
    <div class="p-2 flex-grow-1 bd-highlight card-title"></div>
    <div class="p-2 bd-highlight">
    </div>
</div>

<div class="mt-2 ">
    <table class="table tabela table-hover" id="tabela-archiwum" style="width:100%">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Opublikowane</th>
                <th scope="col">Widoczność</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<script>

    //generowanie tabeli archiwalnych ogłoszeń

    function generateArchivedData() {
        var tableArchive = $('#tabela-archiwum').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            retrieve: true,
            responsive: {
    details: false
            },

            ajax: "{{ route('publications.data', ['active' => 'false']) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name', orderable: true,},
                {data: 'published', name: 'published'},
                {data: 'visibility', name: 'visibility'},
            ],
        });

        $('.tabela tbody').on( 'click', 'tr', function () {
            var data = tableArchive.row( this ).data()
            window.location.href = "/publications/edit/"+data.id;
        });

    };

</script>


