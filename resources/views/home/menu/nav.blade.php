<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-md-offset-2 col-sm-offset-2">
        <ul class="nav navbar-nav side-nav">
            <li class="active">
                <a href="{{ route('menu.edit', $store->id) }}"><i class="fa fa-fw fa-user"></i> 建立/修改項目</a>
            </li>
            <li>
                <a href="{{ route('menu.attr.edit', $store->id) }}"><i class="fa fa-fw fa-key"></i> 建立/修改屬性</a>
            </li>
        </ul>
    </div>
</div>
