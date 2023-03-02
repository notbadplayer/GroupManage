<div class='row'>

    @foreach ($data as $chart => $value)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="border rounded-1 m-1 mb-4">
                <h5 class="card-title text-center">{{ $value['name'] }}</h5>


                <div class="chart" id="chart_{{ $chart }}"
                    data-group-id="{{ $value['group'] ?? null }}"
                    data-subgroup-id="{{ $value['subgroup'] ?? null }}"
                    data-unassigned="{{ $value['unassigned'] ?? null }}"></div>


            </div>
        </div>
    @endforeach
</div>
<script type="module">

    @foreach($data as $chart => $value)

    var options = {
        dataLabels: {
        enabled: true,
        formatter: function (val, opts) {
          return opts.w.globals.series[opts.seriesIndex]
        },
      },
      legend: {
          show: true,
          showForSingleSeries: false,
          showForNullSeries: true,
          showForZeroSeries: true,
          position: 'bottom',
          horizontalAlign: 'center',
          floating: false,
          fontSize: '14px',
          fontFamily: 'Helvetica, Arial',
          fontWeight: 400,
          formatter: undefined,
          inverseOrder: false,
          width: undefined,
          height: undefined,
          tooltipHoverFormatter: undefined,
          customLegendItems: [],
          offsetX: 0,
          offsetY: 0,
          labels: {
              colors: undefined,
              useSeriesColors: false
          },
          markers: {
              width: 12,
              height: 12,
              strokeWidth: 0,
              strokeColor: '#fff',
              fillColors: undefined,
              radius: 12,
              customHTML: undefined,
              onClick: undefined,
              offsetX: 0,
              offsetY: 0
          },
          itemMargin: {
              horizontal: 5,
              vertical: 0
          },
          onItemClick: {
              toggleDataSeries: true
          },
          onItemHover: {
              highlightDataSeries: true
          },
      },

      chart: {
        type: 'pie'
      },
      width: '100%',
      height: '100%',
      series: [{{$value['yes']}}, {{$value['no']}},  {{$value['held']}}],
        labels: ['Tak', 'Nie', 'Brak głosu'],
        colors: ['#198754', '#dc3545', '#C8C8C8']

    }


    //var chart = new ApexCharts(document.querySelector("#chart"), options);
    var chart = new ApexCharts (document.querySelector("#chart_{{$chart}}"), options);

    chart.render();




    @endforeach



    //kliknięcie w wykres
    $('.chart').on('click', function(){
        var htmlText = @include('questionnaires.modalTableResults');

        Swal.fire({
            html: htmlText,
            width: 1000,
            confirmButtonText: 'Zamknij',
            confirmButtonColor: '#0d6efd',

            })

            if(Swal.isVisible())
          {

            var $groupId = $(this).data('group-id');
            var $subGroupId = $(this).data('subgroup-id');
            var $unassigned = $(this).data('unassigned');

            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                });

            var table = $('.tabela').DataTable({
                        processing: true,
                        language: {
                            url: "{{asset('pl.json')}}",
                        },
                        pageLength: 50,
                        serverSide: true,
                        responsive: true,
                        responsive: {
                details: false
                        },
                        ajax: {
                            url:"{{ route('questionnaires.resultsModal') }}",
                            type: 'POST',
                            data: {
                                Questionnaire: "{{$questionnaire->id}}",
                                groupId: $groupId,
                                subGroupId: $subGroupId,
                                unassigned: $unassigned

                            },
                            },
                        columns: [
                            {data: 'user.name', name: 'user.name', orderable: true,},
                            {data: 'user.surname', name: 'user.surname', orderable: true,},
                            {data: 'answer', name: 'answer'},
                        ],
                    });
          }




     })




    </script>
