@pjax('layouts.app')

@section('content')
<link href="{{ asset('assets/avatar/css/avatargenerate.css') }}" rel="stylesheet" />
<style>

/* Container and Builder Viewport */

.builder-viewport {
    background-color: #eae7dc;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    padding: 20px;
}


/* Main Navigation */
.main-navigation {
    background-color: #ffffff;
    padding: 10px;
    display: flex;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    width: 80px;
    flex-shrink: 0;
    height: 350px;
    
}

.main-navigation ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.main-navigation li {
    text-align: center;
}

.main-navigation li.active a {
    background-color: #cccccc;
    border-radius: 8px;
}

.main-navigation li a {
    display: block;
    width: 50px;
    height: 50px;
    text-decoration: none;
    background-repeat: no-repeat;
    background-position: center;
    margin: 0 auto;
    display: flex;
}

/* Original Sub-Menu Icons */
a.male {
    background: url('{{ asset('assets/avatar/img/male.png') }}') no-repeat center 5px;
}

a.female {
    background: url('{{ asset('assets/avatar/img/female.png') }}') no-repeat center center;
}

a.hair {
    background: url('{{ asset('assets/avatar/img/hair-sn.png') }}') no-repeat center center;
}

a.hats {
    background: url('{{ asset('assets/avatar/img/hats.png') }}') no-repeat center center;
}

a.hair-accessories {
    background: url('{{ asset('assets/avatar/img/hair-accessories.png') }}') no-repeat center center;
}

a.glasses {
    background: url('{{ asset('assets/avatar/img/glasses.png') }}') no-repeat center center;
}

a.moustaches {
    background: url('{{ asset('assets/avatar/img/moustaches.png') }}') no-repeat center center;
}

a.tops {
    background: url('{{ asset('assets/avatar/img/top.png') }}') no-repeat center center;
}

a.chest {
    background: url('{{ asset('assets/avatar/img/chest.png') }}') no-repeat center center;
}

a.jackets {
    background: url('{{ asset('assets/avatar/img/jackets.png') }}') no-repeat center center;
}

a.accessories {
    background: url('{{ asset('assets/avatar/img/accessories.png') }}') no-repeat center center;
}

a.bottoms {
    background: url('{{ asset('assets/avatar/img/bottoms-sn.png') }}') no-repeat center center;
}

a.shoes {
    background: url('{{ asset('assets/avatar/img/shoes.png') }}') no-repeat center center;
}

a.belts {
    background: url('{{ asset('assets/avatar/img/belts.png') }}') no-repeat center center;
}

/* Sub-Menu, Clothes, and Colors Sections */
#sub-menu, #clothes, #colors {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    padding: 15px;
    
}

#sub-menu {
    height: 60px;
}

#clothes {
    height: 270px;
    
}

#colors {
    height: 100px;
    margin-left: -100px;
    margin-bottom: 0px;
    margin-top: 0px;

}

#left-side {
    width: 450px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-left: 20px;
    margin-right: 20px;
    
}

#avatar-preview {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f4f4f4;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

#avatar img {
    max-width: 100%;
    border-radius: 8px;
    margin-bottom: 20px;
}

#avatarSelectionForm button {
    background-color: #0069d9;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#avatarSelectionForm button:hover {
    background-color: #0056b3;
}

</style>

<section class="page-content">
<div id="avatarSelector" class="builder-viewport bg-gray-100 shadow-lg rounded-lg p-4 flex">
    <!-- Main Navigation -->
<!-- Main Navigation -->
<div class="main-navigation flex flex-col items-center space-y-4 bg-white rounded-lg shadow p-4 w-20">
    <ul class="space-y-4 w-full">
        <li class="active w-full">
            <a href="#" data-navigate="hd" data-subnav="gender" class="flex items-center justify-center w-full h-12 rounded-lg hover:bg-gray-100 transition-colors ">
                <img src="{{ asset('assets/avatar/img/body.png') }}" alt="Body" class="w-8 h-8 object-contain" />
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-navigate="hr" data-subnav="hair" class="flex items-center justify-center w-full h-12 rounded-lg hover:bg-gray-100 transition-colors">
                <img src="{{ asset('assets/avatar/img/hair.png') }}" alt="Hair" class="w-8 h-8 object-contain" />
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-navigate="ch" data-subnav="tops" class="flex items-center justify-center w-full h-12 rounded-lg hover:bg-gray-100 transition-colors">
                <img src="{{ asset('assets/avatar/img/tops.png') }}" alt="Tops" class="w-8 h-8 object-contain" />
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-navigate="lg" data-subnav="bottoms" class="flex items-center justify-center w-full h-12 rounded-lg hover:bg-gray-100 transition-colors">
                <img src="{{ asset('assets/avatar/img/bottoms.png') }}" alt="Bottoms" class="w-8 h-8 object-contain" />
            </a>
        </li>
        <li class="w-full">
            <a href="#" class="flex items-center justify-center w-full h-12 rounded-lg hover:bg-gray-100 transition-colors">
                <img src="{{ asset('assets/avatar/img/saved-looks.png') }}" alt="Saved Looks" class="w-8 h-8 object-contain" />
            </a>
        </li>
    </ul>
</div>
    <!-- End Main Navigation -->

    <!-- Left Side Content -->
    <div id="left-side">
        <!-- Sub-Menu -->
        <div id="sub-menu" class="sub-navigation">
        <ul id="gender" class="display">

<li>
    <a href="#" class="male nav-selected" data-gender="M"></a>
</li>

<li>
    <a href="#" class="female" data-gender="F"></a>
</li>
</ul>

<ul id="hair" class="hidden">

<li>
    <a href="#" class="hair nav-selected" data-navigate="hr"></a>
</li>

<li>
    <a href="#" class="hats" data-navigate="ha"></a>
</li>

<li>
    <a href="#" class="hair-accessories" data-navigate="he"></a>
</li>

<li>
    <a href="#" class="glasses" data-navigate="ea"></a>
</li>

<li>
    <a href="#" class="moustaches" data-navigate="fa"></a>
</li>


</ul>

<ul id="tops" class="hidden">

<li>
    <a href="#" class="tops nav-selected" data-navigate="ch"></a>
</li>

<li>
    <a href="#" class="chest" data-navigate="cp"></a>
</li>

<li>
    <a href="#" class="jackets" data-navigate="cc"></a>
</li>

<li>
    <a href="#" class="accessories" data-navigate="ca"></a>
</li>
</ul>

<ul id="bottoms" class="hidden">

<li>
    <a href="#" class="bottoms nav-selected" data-navigate="lg"></a>
</li>

<li>
    <a href="#" class="shoes" data-navigate="sh"></a>
</li>

<li>
    <a href="#" class="belts" data-navigate="wa"></a>
</li>

</ul>

        </div>

        <!-- Clothes -->
        <div id="clothes">
            <p>Clothes Content Here</p>
        </div>
        <!-- Colors -->
        <div id="colors" class="colors-menu" >
            <p>Color Content Here</p>
        </div>
    </div>

    <div id="avatar-preview">
    <img id="myHabbo" src="{{ $avatarUrl ?? '' }}" alt="My Habbo" title="My Habbo" class="mb-4" />
    <form action="{{ route('user.avatar.store') }}" id="avatarSelectionForm" method="POST" class="w-full flex flex-col items-center">
        @csrf
        <input type="hidden" name="habbo-avatar" id="avatar-code" value="{{ $avatarConfig ?? '' }}">
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mt-4">Save Changes</button>
    </form>
</div>
</section>

<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/avatar/js/jquery.avatargenerate.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        // Initialize the avatar generator with the current configuration
        var AG = new AvatarGenerate();
        var currentConfig = "{{ $avatarConfig ?? '' }}";
        
        if (currentConfig) {
            AG.importFigure(currentConfig);
        } else {
            // Use default configuration if no user configuration is available

        }

        // Set up initial state
        AG.setCurrentSet("hd");
        AG.loadToClothes("hd");
        AG.loadToColors("hd");
        AG.updateAvatar();

        // Set up event handlers
        $('.main-navigation li a').on('click', function(e){
            e.preventDefault();
            var target = $(this).data('subnav');
            var navigate = $(this).data('navigate');
            $('#sub-menu ul').addClass('hidden');
            $('#' + target).removeClass('hidden');
            $('.main-navigation li').removeClass('active');
            $(this).parent().addClass('active');
            
            if (navigate) {
                AG.setCurrentSet(navigate);
                AG.loadToClothes(navigate);
                AG.loadToColors(navigate);
            }
        });

        $('.sub-navigation a').on('click', function(e){
            e.preventDefault();
            $(this).closest('ul').find('a').removeClass('nav-selected');
            $(this).addClass('nav-selected');
            
            var navigate = $(this).data('navigate');
            if (navigate) {
                AG.setCurrentSet(navigate);
                AG.loadToClothes(navigate);
                AG.loadToColors(navigate);
            }
        });

        // Update avatar code when form is submitted
        $('#avatarSelectionForm').on('submit', function() {
            $('#avatar-code').val(AG.buildFigure() + "&gender=" + AG.getGender());
        });

        // Initialize clothes and colors
        $('#clothes').setClothes();
        $('#colors').setColors();
        $('#avatar-code').setCodeReceiver();
    });
</script>
@endsection