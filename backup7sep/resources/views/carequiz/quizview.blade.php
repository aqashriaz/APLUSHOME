@extends('layouts.admin_header')
@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                   <span class="h2 fontfamily pt-5">Quiz 1 View</span>
         
            </div>
            <div class="col-md-6">
                <div class="float-right">
                    <button type="submit" class="btn regbtn2">SEE RESULTS</button>
                </div>
            </div>
        </div>

        @foreach($quizupdate as $row)
            <div class="row">
            <div class="row p-5 m-auto">
            <label class="label-control lbl2">Question 1</label>
<textarea class="iptx form-control" name="question" placeholder="How was your day?" cols="100" rows="5">{{$row->question}} </textarea>          </div>
        </div>  



    <div class="row mt-2">
    <div class="col-md-3 col-sm-12 col-xs-12">
    <label for="Name" class="control-label float-right">A.</label>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
    <input type="text" class="abcd1 form-control" placeholder="Add Answer"  name="a" value="{{$row->answer_a}}" >

    </div> 
    </div>

    <div class="row mt-2">
    <div class="col-md-3 col-sm-12 col-xs-12">
    <label for="Name" class="control-label float-right">B.</label>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
    <input type="text" class="abcd1 form-control" placeholder="Add Answer"  name="a" value="{{$row->answer_b}}" >

    </div>          
    </div>
    <div class="row mt-2">
    <div class="col-md-3 col-sm-12 col-xs-12">
    <label for="Name" class="control-label float-right">C.</label>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
    <input type="text" class="abcd1 form-control" placeholder="Add Answer"  name="a" value="{{$row->answer_c}}" >

    </div>          
    </div>
    <div class="row mt-2">
    <div class="col-md-3 col-sm-12 col-xs-12">
    <label for="Name" class="control-label float-right">D.</label>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
    <input type="text" class="abcd1 form-control" placeholder="Add Answer"  name="a" value="{{$row->answer_d}}" >

    </div>          
    </div>
    @endforeach


    </div>

</div>


@endsection
