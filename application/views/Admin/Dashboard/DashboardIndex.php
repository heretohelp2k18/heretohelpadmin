<div class="col-xs-12">
    <h1>Dashboard</h1>
    <div class="col-xs-12 no-gutter">
        <div class="col-xs-4">
            <a href="/admin/appUsers/?qtype=User">
                <div class="col-xs-12 col-sm-4 dash-item dash-item-1" style="background:#cd1a57;">
                    <i class="fa fa-user"></i>
                </div>
                <div class="col-xs-12 col-sm-8 dash-item" style="background:#e91e63;">
                    <span class="count-label">Users</span>
                    <span class="count"><?php echo $usercount;?></span>
                </div>
            </a>
        </div>
        
        <div class="col-xs-4">
            <a href="/admin/appUsers/?qtype=Psychologist">
                <div class="col-xs-12 col-sm-4 dash-item dash-item-1" style="background:#00a5ba;">
                    <i class="fa fa-vcard"></i>
                </div>
                <div class="col-xs-12 col-sm-8 dash-item" style="background:#00bcd4;">
                    <span class="count-label">Psychologists</span>
                    <span class="count"><?php echo $psychcount;?></span>
                </div>
            </a>
        </div>
        
        <div class="col-xs-4">
            <a href="/admin/appUsers/?qtype=Pending">
                <div class="col-xs-12 col-sm-4 dash-item dash-item-1" style="background:#d35400;">
                    <i class="fa fa-server"></i>
                </div>
                <div class="col-xs-12 col-sm-8 dash-item" style="background:#e67e22;">
                    <span class="count-label">Pending Applications</span>
                    <span class="count"><?php echo $pendpsychcount;?></span>
                </div>
            </a>
        </div>
    </div>
</div>
<style>
.dash-item
{
    padding: 9px 15px;
    color: #ffffff;
}

.dash-item-1
{
    padding: 25px;
    text-align: center;
}

.dash-item i
{
    font-size: 50px;
    margin: 0 auto;
    display: block;
}

.dash-item span
{
    margin: 0 auto;
    display: block;
}

.dash-item span.count-label
{
    font-size: 18px;
}

.dash-item span.count
{
    font-size: 40px;
}

</style>