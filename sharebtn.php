<div class="widget  widget_share">
	<h4 class="sidebar__headings">Поделиться в соцсетях</h4>
	<div class="textwidget">
		<div class="share">
			<a title="Поделиться ВКонтакте" class="ang_vk" href="http://vkontakte.ru/share.php?url=<?php if(is_home() || is_front_page()){ bloginfo('url');} 
			elseif(is_single()||is_page()) { the_permalink(); } else{ bloginfo('url'); }?>" target="_blank" rel="nofollow"><i class="fa fa-vk" aria-hidden="true"></i></a>
			<a title="Поделиться в Facebook" class="ang_fb"  href="http://facebook.com/sharer.php?url=<?php if(is_home() || is_front_page()){ bloginfo('url');} 
			elseif(is_single()||is_page()) { the_permalink(); } else{ bloginfo('url'); }?>" target="_blank"  rel="nofollow"><i class="fa fa-facebook" aria-hidden="true"></i></a>
			<a title="Добавить в Twitter" class="ang_tw"  href="https://twitter.com/home?status=<?php if(is_home() || is_front_page()){ bloginfo('url');} 
			elseif(is_single()||is_page()) { the_permalink(); } else{ bloginfo('url'); }?>" target="_blank" rel="nofollow"><i class="fa fa-twitter" aria-hidden="true"></i></a>
		</div>
	</div>
</div>