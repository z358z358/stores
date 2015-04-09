<div class="col-md-8 col-md-offset-2">
    <div class="navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li class="active">
                <a href="{{ route('menu.edit', $store->id) }}"><i class="fa fa-fw fa-user"></i> 建立/修改項目</a>
            </li>
            <li>
                <a href="{{ route('menu.attr.edit', $store->id) }}"><i class="fa fa-fw fa-key"></i> 建立/修改屬性</a>
            </li>
            <!--<li>
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse">
                    <li>
                        <a href="#">Dropdown Item</a>
                    </li>
                    <li>
                        <a href="#">Dropdown Item</a>
                    </li>
                </ul>
            </li>-->
        </ul>
    </div>
</div>