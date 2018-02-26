<div class="middle-part detail-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="widget">
					<h2>Visualisation</h2>
					<ul class="visualisations-ul"></ul>
					<a href="javascript:;" class="add-link add-visualisation-link" ng-show="user_type!='Viewer' && user_type!=''"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add visualisation</a>
					<div class="add-visualisation-form">
						<form class="frm-main" name="addvis" id="addvis" method="post" action="scripts/process.php" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" ng-model="addvis" onsubmit="return chkAddVisForm();">
							<input type="hidden" name="act" id="act" value="add-visualisation" />
							<input type="hidden" name="dsid" id="dsid" value="{{rec.id}}" />
							<h4>Add visualisation</h4>
							<div class="row">
								<div class="col-sm-12">
									<input type="text" name="title" id="title" placeholder="Title" value="" ng-required="true" ng-model="formData.title" />
									<span class="error-msg" ng-show="addvis.title.$touched && addvis.title.$error.required">Title is Required.</span>
									<span class="error-msg" ng-show="titleError">{{titleError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<input type="text" name="url" id="url" placeholder="Url" value="" ng-model="formData.url" />
									<span class="error-msg" ng-show="urlError">{{urlError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<input type="file" name="image" id="image" valid-file placeholder="Image" value="" ng-model="formData.image" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<textarea name="description" id="description" placeholder="Description" ng-required="true" value="" ng-model="formData.description"></textarea>
									<span class="error-msg" ng-show="addvis.description.$touched && addvis.description.$error.required">Description is Required.</span>
									<span class="error-msg" ng-show="descriptionError">{{descriptionError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="addvis.$pending || addvis.$invalid || loading" ng-class="{ 'disabled-button' : addvis.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="addvis.$pending || loading"></i> Add</button>
								</div>
								<div class="col-sm-6">
									<a href="javascript:;" class="btn btn-md add-visualisation-link">Cancel</a>
								</div>
							</div>
						</form>
					</div>
					<!--<h2>Features</h2>
					<ul class="featured-ul">
						<li draggable="true" ondragstart="dragModal(event)"><a href="javascript:;" class="cursor-move" id="Rating">Rating</a></li>
						<li draggable="true" ondragstart="dragModal(event)"><a href="javascript:;" class="cursor-move" id="LikeDislikes">Likes & Dislikes</a></li>
					</ul>-->
					<div class="drag-drop-cont" ondrop="dropModal(event)" ondragover="allowDrop(event)">Drop visualisation here</div>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="img-cont"></div>
				<h1>{{title}}</h1>
				<div class="row ds-meta">
					<div class="col-sm-4">
						<b>Author: </b> {{author}}
					</div>
					<div class="col-sm-4">
						<b>Created on: </b> {{created_on}}
					</div>
					<div class="col-sm-4">
						<b>Last updated</b> {{last_updated}}
					</div>
				</div>
				<div class="row" style="margin-bottom:20px;">
					<div class="col-sm-12">
						<div class="staticContent justify_text"></div>
					</div>
				</div>
				<!--<div class="other-meta">
					<div class="row">
						<div class="col-xs-6">
							<div class="rating-cont"></div>
						</div>
						<div class="col-xs-6 like-dislike-outer">
							<div class="like-dislike-cont">
								<a href="javascript:;" class="dislike-cont" onclick="likeDislikeDS('Dislike');"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <span class="num-dislikes">{{rec.num_dislikes}}</span>
							</div>
							<div class="like-dislike-cont">
								<a href="javascript:;" class="like-cont" onclick="likeDislikeDS('Like');"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <span class="num-likes">{{rec.num_likes}}</span>
							</div>
						</div>
					</div>
				</div>
				<div class="comments">
					<div class="comment-form">
						<form class="frm-main" name="comment" id="comment" method="post" ng-submit="submitCommentForm(comment.$valid)" novalidate="novalidate" autocomplete="off" ng-model="comment">
							<input type="hidden" ng-model="commentData.dspid" name="dspid" id="dspid" value="{{rec.id}}" />
							<div class="row">
								<div class="col-md-10 col-sm-9">
									<textarea name="message" id="message" placeholder="Write a comment" ng-required="true" value="" ng-model="commentData.message"></textarea>
									<span class="error-msg" ng-show="comment.message.$touched && comment.message.$error.required">Please write some comment.</span>
									<span class="error-msg" ng-show="messageError">{{messageError}}</span>
								</div>
								<div class="col-md-2 col-sm-3">
									<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="comment.$pending || comment.$invalid || loading1" ng-class="{ 'disabled-button' : comment.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="comment.$pending || loading1"></i> Post</button>
								</div>
							</div>
						</form>
					</div>
					<div class="comments-archive ds-comments-archive"></div>
				</div>-->
				
				<div class="visualisations-cont">
					<div class="visualisations-list"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="addFeatureDialog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add/Remove Feature(s) To Viualisation</h4>
      </div>
      <div class="modal-body">
	  	<form class="frm-main" name="addupdfeature" id="addupdfeature" method="post" action="scripts/process.php" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" onsubmit="return chkAddFeatToVis();">
			<input type="hidden" name="act" id="act" value="addupdatefeaturetovis" />
			<input type="hidden" name="current_dsid" id="current_dsid" value="" />
			<input type="hidden" name="current_visid" id="current_visid" value="" />
			<div class="row bottom-margin">
				<div class="col-sm-6">
					<input type="checkbox" name="chkFeature[]" id="chk_rating" value="Rating" /> Rating
				</div>
				<div class="col-sm-6">
					<input type="checkbox" name="chkFeature[]" id="chk_likedislike" value="LikeDislike" /> Likes & Dislikes
				</div>
			</div>
			<div class="row bottom-margin">
				<div class="col-sm-6">
					<input type="checkbox" name="chkFeature[]" id="chk_discussion" value="Discussion" /> Discussion
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-md btn-primary">Update</button>
				</div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="ratingDialog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Provide Rating</h4>
      </div>
      <div class="modal-body">
	  	<form class="frm-main" name="updatevis" id="updatevis" method="post" action="scripts/process.php" ng-model="updatevis" onsubmit="return chkUpdateRatingForm();">
			<input type="hidden" name="rvis_id" id="rvis_id" value="" />
			<input type="hidden" name="rdsid" id="rdsid" value="{{rec.id}}" />
			<div class="row">
				<div class="col-sm-12">
					<div class="dynamic-rating"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-md btn-primary">Update</button>
				</div>
			</div>
      	</form>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="editVisDialog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Visualisation</h4>
      </div>
      <div class="modal-body">
        <form class="frm-main" name="updatevis" id="updatevis" method="post" action="scripts/process.php" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data" ng-model="updatevis" onsubmit="return chkUpdateVisForm();">
			<input type="hidden" name="act" id="act" value="update-visualisation" />
			<input type="hidden" name="vis_id" id="vis_id" value="" />
			<input type="hidden" name="vdsid" id="vdsid" value="{{rec.id}}" />
			<div class="row">
				<div class="col-sm-12">
					<input type="text" name="title1" id="title1" placeholder="Title" value="" />
					<span class="error-msg" ng-show="updatevis.title1.$touched && updatevis.title1.$error.required">Title is Required.</span>
					<span class="error-msg" ng-show="title1Error">{{title1Error}}</span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<input type="text" name="url1" id="url1" placeholder="Url" value="" />
					<span class="error-msg" ng-show="url1Error">{{url1Error}}</span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<input type="file" name="image1" id="image1" placeholder="Image" value="" />
					<span id="vis-image-cont"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<textarea name="description1" id="description1" placeholder="Description" value=""></textarea>
					<span class="error-msg" ng-show="updatevis.description1.$touched && updatevis.description1.$error.required">Description is Required.</span>
					<span class="error-msg" ng-show="description1Error">{{description1Error}}</span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="updatevis.$pending || updatevis.$invalid" ng-class="{ 'disabled-button' : updatevis.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="updatevis.$pending"></i> Update</button>
				</div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>