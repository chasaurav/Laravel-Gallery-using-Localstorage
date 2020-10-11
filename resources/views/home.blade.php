@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-8"><h1>Save Images</h1></div>
    <div class="col-2">
      <button type="button" id="addNewImage" class="btn btn-primary btn-block">Add Image</button>
    </div>
    <div class="col-2">
      <button type="button" id="deleteAllImage" class="btn btn-danger btn-block">Delete All Images</button>
    </div>
  </div>
  <div class="row">
    <div class="owl-carousel owl-theme dumpImg"></div>
  </div>
  <div class="cust_modal" style="display: none;">
    <input type="file" id="fileInput">
    <p class="alertMsg"></p>
    <button type="button" id="closeModal" class="custCloseButton">Close</button>
  </div>
</div>
@endsection
