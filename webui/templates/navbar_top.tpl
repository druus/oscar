
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">OSCAR Asset Register</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a>No of assets <span class="badge">{{ cfgData.assetCnt }}</span></a></li>
            <li>
              <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  User: {{ cfgData.userName }} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a>Server: {{ cfgData.dbServer }}</a></li>
                  <li><a>Database: {{ cfgData.dbName }}</a></li>
                </ul>
              </div>
            <li><a href="#">Help</a></li>

            <li><a href="index.php?cmd=logout">Logout</a></li>

          </ul>
          <form class="navbar-form navbar-right" method="post" action="/~daniel/oscar_v3/index.php">
            <input type="hidden" name="cmd" value="edit">
            <input type="text" name="asset" class="form-control" placeholder="Asset no">
          </form>
        </div>
      </div>
</nav>

