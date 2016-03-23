<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican2#license
 */

use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;

\meican\circuits\assets\reservation\Create::register($this);

$this->params['hide-content-section'] = true;
$this->params['hide-footer'] = true;

$form = ActiveForm::begin([
        'method' => 'post',
        'id' => 'reservation-form',
]) 

?>

<div id="lsidebar" class="lsidebar collapsed">
    <!-- Nav tabs -->
    <div class="lsidebar-tabs">
        <ul role="tablist">
            <li><a title="Welcome to the reservation page" href="#home" role="tab"><i class="fa fa-info-circle"></i></a></li>
            <li><a title="Select your endpoints" href="#path" role="tab"><i class="fa"><img src="https://maxcdn.icons8.com/Android_L/PNG/24/Maps/route-24.png" width="21"></i></a></li>
            <li><a title="Set the circuit requirements" href="#requirements" role="tab"><i class="fa fa-sliders"></i></a></li>
            <li><a title="Choose the circuit duration" href="#schedule" role="tab"><i class="fa fa-calendar"></i></a></li>
            <li><a title="Confirm and submit" href="#confirm" role="tab"><i class="fa fa-check danger"></i></a></li>
        </ul>

        <ul role="tablist">
            <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="lsidebar-content">
        <div class="lsidebar-pane" id="path">
            <h1 class="lsidebar-header">
                Step 1: Path
                <span class="lsidebar-close"><i class="fa fa-caret-left"></i></span>
            </h1>
            <br>
            <div class="nav-tabs-custom" style="margin-right: 15px;">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-search"></i></a></li>
                  <li><a id="add-point" href="#"><i class="fa fa-plus"></i> <i class="fa fa-map-marker"></i></a></li>
                  <li><a href="#"><i class="fa fa-file-text"></i></a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <div class="input-group input-group-sm">
                        <!-- /btn-group -->
                            <input type="text" class="form-control" placeholder="Enter a domain, device, port or URN">
                            <div class="input-group-btn">
                              <button type="button" class="btn btn-primary"><span class="fa fa-search"></span></button>
                            </div>
                          </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_3">
                        <button type="button" class="btn btn-default"><span class="fa fa-plus"></span> Import path</button>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <div>
                <ul id="path" class="timeline">
                    <li class="time-label">
                          <span class="bg-gray">
                            <i class="fa fa-laptop"></i>
                            Source
                          </span>
                    </li>
                    <!-- timeline item -->
                    <li class="point">
                        <!-- timeline icon -->
                        <i class="fa fa-map-marker bg-gray"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">
                                <label data="" class="point-info dom-l">none</label>
                                <div class="pull-right">
                                    <a href="#" class="text-muted"><i class="fa fa-plus"></i></a>
                                    <a href="#" class="text-muted"><i class="fa fa-arrow-up"></i></a>
                                    <a href="#" class="text-muted"><i class="fa fa-arrow-down"></i></a>
                                </div>
                          </h3>
                        <div class="timeline-body" hidden>
                            <div class="point-default">
                              Network: <label data="" class="point-info net-l">none</label><br>
                              Device: <label data="" class="point-info dev-l">none</label><br>
                              Port: <label class="point-info port-l">none</label><br>
                              <input class="port-id" type="hidden" name="ReservationForm[path][port][]">
                            </div>
                            <div class="point-advanced" hidden>
                              URN: <label class="point-info urn-l">none</label><br>
                              <input class="urn" type="hidden" name="ReservationForm[path][urn][]">
                            </div>
                            VLAN: <label class="point-info vlan-l">Auto</label>
                            <input class="vlan" type="hidden" name="ReservationForm[path][vlan][]">
                            <div class="pull-right">
                                <a href="#" class="text-muted"><i class="fa fa-pencil"></i></a>
                                <a href="#" class="text-muted"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                      </div>
                    </li>
                    <li class="point">
                        <!-- timeline icon -->
                        <i class="fa fa-map-marker bg-gray"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">
                                <label data="" class="point-info dom-l">none</label>
                                <div class="pull-right">
                                    <a href="#" class="text-muted"><i class="fa fa-plus"></i></a>
                                    <a href="#" class="text-muted"><i class="fa fa-arrow-up"></i></a>
                                    <a href="#" class="text-muted"><i class="fa fa-arrow-down"></i></a>
                                </div>
                            </h3>
                            <div class="timeline-body" hidden>
                                <div class="point-default">
                                  Network: <label data="" class="point-info net-l">none</label><br>
                                  Device: <label data="" class="point-info dev-l">none</label><br>
                                  Port: <label class="point-info port-l">none</label><br>
                                  <input class="port-id" type="hidden" name="ReservationForm[path][port][]">
                                </div>
                                <div class="point-advanced" hidden>
                                  URN: <label class="point-info urn-l">none</label><br>
                                  <input class="urn" type="hidden" name="ReservationForm[path][urn][]">
                                </div>
                                VLAN: <label class="point-info vlan-l">Auto</label>
                                <input class="vlan" type="hidden" name="ReservationForm[path][vlan][]">
                                <div class="pull-right">
                                    <a href="#" class="text-muted"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="text-muted"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->
                    <li id="destination-client" class="time-label">
                      <span class="bg-gray">
                        <i class="fa fa-laptop"></i>
                        Destination
                      </span>
                    </li>
                </ul>
            </div>
            <div class="pull-right">
                <button type="button" class="next-btn btn btn-primary"><span class="fa fa-arrow-right"></span> Next step</button>
            </div><br><br><br>
        </div>

        <div class="lsidebar-pane" id="requirements">
            <h1 class="lsidebar-header">Step 2: Requirements<span class="lsidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            <br>
            <div class="form-group">
                <label>Bandwidth</label>
                <div class="input-group">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-primary"><span class="fa fa-minus"></span></button>
                    </div>
                    <input type="text" class="form-control" placeholder="Mbps" name="ReservationForm[bandwidth]">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-primary"><span class="fa fa-plus"></span></button>
                    </div>
                </div>
            </div>
            <div class="form-group" hidden>
                <label>Protection</label> <i class="fa fa-question-circle" data-toggle="tooltip" title="A protected circuit means that you accept losing the guaranteed bandwidth, but requires availability of the service."></i>
                <br>
                <input type="checkbox" checked data-toggle="toggle" name="ReservationForm[protection]">
            </div>
            <br>
            <div class="pull-right">
                <button type="button" class="next-btn btn btn-primary"><span class="fa fa-arrow-right"></span> Next step</button>
            </div> 
        </div>

        <div class="lsidebar-pane" id="schedule">
            <h1 class="lsidebar-header">Step 3: Schedule<span class="lsidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            <div class="form-group"><br>
                <label>Date and time range:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control" date-range="enabled" name="ReservationForm[date_range]">
                </div>
                <!-- /.input group -->
              </div><br>
            <div class="pull-right">
                <button type="button" class="next-btn btn btn-primary"><span class="fa fa-arrow-right"></span> Next step</button>
            </div>
        </div>

        <div class="lsidebar-pane" id="confirm">
            <h1 class="lsidebar-header">Step 4: Confirmation<span class="lsidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            <br>
            <label>Name:</label>
            <input type="text" class="form-control" name="ReservationForm[name]"><br>
            <div class="pull-right">
                <button type="button" class="next-btn btn btn-primary"><span class="fa fa-arrow-right"></span> Submit</button>
            </div>
        </div>

        <div class="lsidebar-pane" id="home">
            <h1 class="lsidebar-header">Welcome to reservation page<span class="lsidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            <br><p>
                This is the reservation page. Here you can make a circuit reservation very quickly and easily.<br><br>Listed below you can see all steps involved in a circuit reservation:
            </p><br>
            <p>1. Select your endpoints.</p>
            <p>2. Define the requirements, e.g., bandwidth.</p>
            <p>3. Set the duration of the circuit.</p>
            <p>4. Confirm your request and submit.</p>

            <div class="pull-right">
                <button type="button" class="next-btn btn btn-primary"><span class="fa fa-arrow-right"></span> Start</button>
            </div>
        </div>

        <div class="lsidebar-pane" id="settings">
            <h1 class="lsidebar-header">Settings<span class="lsidebar-close"><i class="fa fa-caret-left"></i></span></h1>
            <br>
            <button id="switch-mode" type="button" class="btn btn-primary"><span class="fa fa-arrow-right"></span> Switch</button>
        </div>
    </div>
</div>

<div id="canvas" class="lsidebar-map"></div>

<?php ActiveForm::end();

Modal::begin([
    'id' => 'point-modal',
    'header' => 'Edit point',
    'footer' => '<button class="cancel-btn btn btn-default">Cancel</button> <button class="save-btn btn btn-primary">Save</button>',
]); ?>

<label class="point-position" hidden></label>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#p1" data-toggle="tab">Normal</a></li>
      <li><a href="#p2" data-toggle="tab">Advanced</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="p1">
        Domain
        <select id="dom-select" class="form-control" disabled>
        </select><br>
        Network
        <select id="net-select" class="form-control" disabled>
        </select><br>
        Device
        <select id="dev-select" class="form-control" disabled>
        </select><br>
        Port
        <select id="port-select" class="form-control" disabled>
        </select><br>
        VLAN
        <select id="vlan-select" class="form-control" disabled>
        </select>
      </div>
      <!-- /.tab-pane -->
      <div class="tab-pane" id="p2">
        URN
        <div class="form-group">
          <input id="urn" type="text" class="form-control" placeholder="URN">
        </div>
        VLAN
        <div class="form-group">
          <input id="vlan" type="text" class="form-control" value="Auto">
        </div>
      </div>
      <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>


<?php Modal::end(); ?>
