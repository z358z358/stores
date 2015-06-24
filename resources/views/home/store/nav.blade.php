<div class="row">

    @if ($store->has_item)

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"></div>
                        <div></div>
                    </div>
                </div>
            </div>
            <a href="{{ route('menu.show', $store->slug) }}">
                <div class="panel-footer">
                    <span class="pull-left">前往點餐!</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    @endif

    @if ($store->owner)

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"></div>
                        <div></div>
                    </div>
                </div>
            </div>
            <a href="{{ route('menu.edit', $store->id) }}">
                <div class="panel-footer">
                    <span class="pull-left">建立/編輯商品!</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"></div>
                        <div></div>
                    </div>
                </div>
            </div>
            <a href="{{ route('store.order', $store->slug) }}">
                <div class="panel-footer">
                    <span class="pull-left">檢視目前訂單!</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    @endif
</div>