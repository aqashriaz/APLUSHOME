@include('layouts.admin_header')
<div class="page-wrapper">
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row mt-5">
            <!-- Column -->
            <div class="col-md-12">

             <nav>
               
                    <div class="row">
                        <div class="col-md-4 col-sm-12">

                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link tab_css active" id="nav-Client-tab" data-toggle="tab" href="#nav-Client" role="tab" aria-controls="nav-Client" aria-selected="true">Client
                                </a>

                                <a class="nav-item nav-link tab_css" id="nav-Caregiver-tab" data-toggle="tab" href="#nav-Caregiver" role="tab" aria-controls="nav-Caregiver" aria-selected="false">Caregiver Feedback</a>

                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12"></div>
                        <div class="col-md-4 col-sm-12">
                         <div class="row">
                            <div class="col-md-12">
                                <input type="date" class="form-control" name="timeEnd" placeholder="Select Date" required>
                            </div> 
                        </div>
                    </div>
                </div>
           
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">


            <div class="tab-pane fade show active" id="nav-Client" role="tabpanel" aria-labelledby="nav-Client-tab">
            @foreach($view as $row)
<div class="card">
 <div class="card-body">
        <table class="p-3">
            <tbody>
             <tr>

                <td style="width:50px;"><span class="round">                               
                  @if($row->image) 
                  <img src="{{url('public/clients/'.$row->image)}}" alt="view" class="img-circle" style="height: 70px" width="70px">
                  @else
                  <img src="{{url('public/images/person.png')}}" alt="view" class="img-circle" style="height: 50px">
                  @endif
              </span>
          </td>
          <td><h6>{{$row->client_id}}</h6><small class="text-muted">{{$row->email}}</small></td>

      </tr>
      <tr class="mt-3">
        <td colspan="2">
            <h5 class="text-justify">Adams is very friendly proffesional</h5> <br>
            <p>{{$row->client_feedback_desc}}</p>
        </td>
    </tr>
</tbody>


</table>
</div>
</div>
@endforeach
</div>

<div class="tab-pane fade show fade" id="nav-Caregiver" role="tabpanel" aria-labelledby="nav-Caregiver-tab">
        @foreach($view as $row)
<div class="card">
 <div class="card-body">
        <table class="p-3">
            <tbody>
             <tr>

                <td style="width:50px;"><span class="round">                               
                  @if($row->image) 
                  <img src="{{url('public/clients/'.$row->image)}}" alt="view" class="img-circle" style="height: 70px" width="70px">
                  @else
                  <img src="{{url('public/images/person.png')}}" alt="view" class="img-circle" style="height: 50px">
                  @endif
              </span>
          </td>
          <td><h6>{{$row->client_id}}</h6><small class="text-muted">{{$row->email}}</small></td>

      </tr>
      <tr class="mt-3">
        <td colspan="2">
            <h5 class="text-justify">Adams is very friendly proffesional</h5> <br>
            <p>{{$row->client_feedback_desc}}</p>
        </td>
    </tr>
</tbody>


</table>
</div>
</div>
@endforeach

</div>

</div>

</div>
</div>
</div>
</div>
