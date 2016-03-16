@if(auth()->user())
<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
           	    @if(isset($menu))
                        @foreach($menu as $key=>$nav)
                          @if($nav['sub'])
                            <li>
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="{{$nav['class']}}"></span> {{$nav['name']}}
                                <span class="caret"></span>
                              </a>
                                  <ul class="dropdown-menu">
                                    @foreach($nav['sub'] as $sub)
                                      <li>
                                        <a href="{{$sub['url']}}">
                                          <span class="{{$sub['class']}}"></span> {{$sub['name']}}
                                        </a>
                                      </li>
                                    @endforeach
                                  </ul>                          
                            </li> 
                            @else
                            <li>
                                <a href="{{$nav['url']}}"> 
                                  <span class="{{$nav['class']}}"></span> {{$nav['name']}}
                                </a>
                            </li> 
                            @endif
                    @endforeach
                @endif
          </ul>
          <ul class="nav navbar-nav navbar-right">
<!--             <li><a href="#">Setting</a></li> -->
<!--             <li><a href="#">Profile</a></li> -->
            <li class="active"><a href="{{route('logout')}}">Logout<span class="sr-only">(current)</span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
</nav>
@endif