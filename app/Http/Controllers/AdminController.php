<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\apartment_detail;

class AdminController extends Controller
{
    public function deleteOwnerPandingPosts(Request $Request)
    {
        apartment_detail::where('id',$Request->id)->update(['active_status'=>2]);
    }

    public function acceptOwnerPandingPosts(Request $Request)
    {
        apartment_detail::where('id',$Request->id)->update(['active_status'=>1]);
    }

    public function ownerPandingPosts()
    {
        $apartments = apartment_detail::where('active_status',0)->get();
        ?>
        <table id="order-listing" class="table">
            <thead>
                <tr>
                    <th>Sl NO#</th>
                    <th>Owner name</th>
                    <th>Owner phone</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody >
            <?php
            foreach ($apartments as $apartment) {
                ?>
                <tr>
                    <td><?php echo $apartment->id ?></td>
                    <td><?php echo $apartment->owner->name ?></td>
                    <td><?php echo $apartment->owner->phone ?></td>
                    <td>
                        <Button class="btn btn-primary" data-toggle="modal" data-target="#apertment-details-modal-<?php echo $apartment->id ?>">Apertment Detail</Button>
                    </td>
                    <td>
                        <Button class="btn btn-success" onclick="accept_apartment(<?php echo $apartment->id ?>)">Accept</Button>
                        <Button class="btn btn-danger" onclick="delete_apartment(<?php echo $apartment->id ?>)">Delete</Button>
                    </td>
                </tr>

                <div class="modal fade" tabindex="-1" role="dialog" id="apertment-details-modal-<?php echo $apartment->id ?>">
                    <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                        if ($apartment->featureImage) {
                            ?>
                            <img class="card-img-top mx-auto" style="width: 350px;height: 250px;" src="../Apartment photoes/<?php echo $apartment->featureImage->image; ?>" alt="Card image cap">
                            <?php
                        }
                        ?>
                        <div class="modal-body">
                            <h5 class="card-title"><?php echo $apartment->flat_name; ?></h5>
                            <p class="card-text"><?php echo $apartment->apartment_description; ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Address : <?php echo $apartment->address; ?></li>
                            <li class="list-group-item">Area : <?php echo $apartment->zone.','.$apartment->district; ?></li>
                            <li class="list-group-item">Floor no : <?php echo $apartment->floor_no; ?></li>
                            <li class="list-group-item">Total bed :<?php echo $apartment->total_bed; ?></li>
                            <li class="list-group-item">Total bath :<?php echo $apartment->total_bath; ?></li>
                            <li class="list-group-item">Rent : <?php echo $apartment->apartment_rent; ?></li>
                            <li class="list-group-item">Size : <?php echo $apartment->apartment_size; ?></li>
                        </ul>
                        <?php
                        // if (count($apartment->detailsImages)>0) {
                        ?>
                        <div class="row grid-margin">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Detail images</h4>
                                        <div class="owl-carousel owl-theme loop">
                                            <?php
                                                foreach ($apartment->detailsImages as $image) {
                                                    ?>
                                                    <div class="item">
                                                        <img src="../Apartment photoes/<?php echo $image->image; ?>" alt="image"/>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        // }
                        ?>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
                <?php
            }
            ?>
            </tbody>
        </table>
        <script src="../assets/melody/js/data-table.js"></script>
        <!-- <script src="{{asset('assets/melody')}}/js/data-table.js"></script> -->
        <script src="../assets/melody/js/owl-carousel.js"></script>
        <?php
    }

    public function ownerRegistrationRequests()
    {
        $variable = User::where('user_role','owner')->get();
        ?>
        <table id="order-listing" class="table">
              <thead>
                <tr>
                    <th>Sl NO#</th>
                    <th>Owner name</th>
                    <th>Owner phone</th>
                    <th>Owner email</th>
                    <th>Status</th>
                </tr>
              </thead>
              <tbody >
        <?php
        foreach ($variable as $value) {
            $checked = $value->status=='2'?'checked':'';
            ?>
            <tr>
                <td><?php echo $value->id ?></td>
                <td><?php echo $value->name ?></td>
                <td><?php echo $value->phone ?></td>
                <td><?php echo $value->email ?></td>
                <td>
                <label class="switch">
                    <input type="checkbox" <?php echo $checked; ?> onclick="öwnerApprovel(<?php echo $value->id ?>)">
                    <span class="slider round"></span>
                </label>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
            </table>
            <script src="../assets/melody/js/data-table.js"></script>
        <?php
    }

    public function ownerApproval(Request $Request)
    {
        $user = User::find($Request->id);
        $user->status = $user->status==2?1:2;
        $user->save();
        return "Owner status updated";
    }
}
