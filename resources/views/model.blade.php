<div class="modal fade" id="status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Update Status')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample" method="POST" action="/users/status">
                    @csrf
                    <input type="hidden" name="id" id="stid">
                    <input type="hidden" name="status" id="st">

                    <div class="form-group">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">{{ __('Reason')}}</label>
                                <div class="col-sm-9">
                                <textarea name="reason" class="form-control border-dark"  cols="40" rows="3"></textarea>
                                     </div>
                             </div>
                   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


<div class="modal fade" id="rewards" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Update Status')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample" method="POST" action="/request/update">
                    @csrf
                                        
                    <input type="hidden" name="id" id="request_id">

                    <div class="form-group">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">{{ __('Type')}}</label>
                                <div class="col-sm-9">
                                <select name="type" class="form-control border-dark" >
                                    <option value="Success">Success</option>
                                    <option value="Reject">Reject</option>
                                </select>
                           </div>
                    </div>

                    <div class="form-group">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">{{ __('Remark')}}</label>
                                <div class="col-sm-9">
                                <textarea name="reason" class="form-control border-dark"  cols="10" rows="3" placeholder="youl recevie payment with in 2 days"></textarea>
                                     </div>
                    </div>
                   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>      
        
        
<div class="modal fade" id="updateoffer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Offer Data')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample-banner" method="POST" action="/offer/update" enctype= "multipart/form-data">
                    @csrf
                             
                    <input type="hidden" name="id" id="bannerid"/>                    
                    <input type="hidden" name="oldimage" id="oldimage"/>                    


                    <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Offer Title</label>
                                    <div class="col-sm-12">
                                    <input type="text" name="offer_title" id="offer_title" class="form-control" />
                    </div>
                    </div>
                    
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
                
        
 <!-- Add Promotion Banner-->
  <div class="modal fade" id="bannermodel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Banner Data')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample" method="POST" action="/banner/create" enctype= "multipart/form-data">
                    @csrf
                                        

                    <div class="form-group">
                                <label for="exampleInputEmail2" class="col-sm-12 col-form-label">{{ __('Banner onClick Action')}}</label>
                                <div class="col-sm-12">
                                <select name="type" class="form-control" required>
                                  <option value="spin" >Spin Screen</option>
                                    <option value="video" >Video Task Screen</option>
                                    <option value="web" >Website Task Screen</option>
                                    <option value="link" >Link</option>
                                    <option value="refer" >Referral Screen</option>
                                </select>
                           </div>
                    </div>
                    
                    <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Url( Required only for Banner Action Link)</label>
                                    <div class="col-sm-12">
                                    <input type="text" name="link" class="form-control" />
                                         </div>
                    </div>
               
                    <div class="form-group">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Select Banner (200*400)</label>
                                    <div class="col-sm-12">
                                    <input type="file" name="icon" class="form-control" />
                                         </div>
                    </div>
                    
                    
                   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Add')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        
<!---update promotion banner-->
  <div class="modal fade modal" id="updatebanner" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Banner Data')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample-banner" method="POST" action="/banner/update" enctype= "multipart/form-data">
                    @csrf
                             
                    <input type="hidden" name="id" id="bannersid"/>                    
                    <input type="hidden" name="oldimage" id="oldimagebanner"/>                    

                    <div class="form-group">
                                <label for="exampleInputEmail2" class="col-sm-12 col-form-label">{{ __('Banner onClick Action')}}</label>
                                <div class="col-sm-12">
                                <select name="type" class="form-control" id="type"required>
                                    <option value="spin" >Spin Screen</option>
                                    <option value="video" >Video Task Screen</option>
                                    <option value="web" >Website Task Screen</option>
                                    <option value="link" >Link</option>
                                    <option value="refer" >Referral Screen</option>
                                </select>
                           </div>
                    </div>
                    
                    <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Url( Required only for Banner Action Link)</label>
                                    <div class="col-sm-12">
                                    <input type="text" name="links" id="link" class="form-control" />
                                         </div>
                    </div>
                    
                  
                      <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-12 col-form-label">{{ __('Select Banner 200*400')}}</label>
                                <div class="col-sm-12">
                                    <input id="icon" type="file" class="form-control" name="icon" placeholder="Select">
                                </div>
                     </div>
                    
                    
                   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
     
     
         
 <!-- Add Game-->
  <div class="modal fade" id="gamemodel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Game Data')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample" method="POST" action="/games/create" enctype= "multipart/form-data">
                    @csrf
                                        

                    <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Game Title</label>
                                    <div class="col-sm-12">
                                    <input type="text" name="title" class="form-control" />
                                         </div>
                    </div>
                    
                    <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Game Url</label>
                                    <div class="col-sm-12">
                                    <input type="text" name="link" class="form-control" />
                                         </div>
                    </div>
               
                    <div class="form-group">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Game Thumbnail 200*200</label>
                                    <div class="col-sm-12">
                                    <input type="file" name="icon" class="form-control" />
                              </div>
                    </div>
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Add')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        
<!---update Game-->
  <div class="modal fade modal" id="updategame" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterLabel">{{ __('Game Data')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body bg-white">
                    <form class="forms-sample-banner" method="POST" action="/games/update" enctype= "multipart/form-data">
                    @csrf
                             
                    <input type="hidden" name="id" id="gid"/>                    
                    <input type="hidden" name="oldimage" id="goldimage"/>                    

                   <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Game Title</label>
                                    <div class="col-sm-12">
                                    <input type="text" id="gtitle" name="title" class="form-control" />
                                         </div>
                    </div>
                    
                    <div class="form-group" id="divlink">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Game Url</label>
                                    <div class="col-sm-12">
                                    <input type="text" id="glink" name="link" class="form-control" />
                                         </div>
                    </div>
               
                    <div class="form-group">
                                    <label for="exampleInputEmail2" class="col-sm-12 col-form-label">Game Thumbnail 200*200</label>
                                    <div class="col-sm-12">
                                    <input type="file" name="icon" class="form-control" />
                              </div>
                    </div>
                    
                    
                   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>                