<ul class="nav sidebar-menu">
	@foreach ($menus as $menu)
		<li class="{{$menu->active ? 'active':''}} {{$menu->open or ''}}">
			<a href="{{$menu->path}}" class="{{$menu->submenus->count()>0 ? 'menu-dropdown':''}}">
				<i class="menu-icon {{$menu->icon}}"></i>
				<span class="menu-text"> {{$menu->name}}</span>

				@if ($menu->submenus->count() > 0)
					<i class="menu-expand"></i>
				@endif
			</a>
			@if ($menu->submenus->count() > 0)
				<ul class="submenu">
					@foreach ($menu->submenus as $submenu)
						<li class="{{$submenu->active ? 'active':''}} {{$submenu->open or ''}}">
							<a href="{{$submenu->path}}" class="{{$submenu->submenus->count()>0 ? 'menu-dropdown':''}}">
								{{--<i class="menu-icon {{$submenu->icon}}"></i>--}}
								<span class="menu-text">{{$submenu->name}}</span>
								@if ($submenu->submenus->count() > 0)
									<i class="menu-expand"></i>
								@endif
							</a>

							@if ($submenu->submenus->count() > 0)
								<ul class="submenu">
									@foreach ($submenu->submenus as $sub)
										<li class="{{$sub->active ? 'active':''}} {{$submenu->open or ''}}">
											<a href="{{$sub->path}}">
												{{--<i class="menu-icon {{$submenu->icon}}"></i>--}}
												<span class="menu-text">{{$sub->name}}</span>
											</a>

										</li>
									@endforeach
								</ul>
							@endif

						</li>
					@endforeach
				</ul>
			@endif
		</li>
	@endforeach


</ul>
