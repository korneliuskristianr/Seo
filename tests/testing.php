<?php

 // return $seo = SeoWebsite::setSiteName('Article')->setTitle('Cool')->render();	 
	// return $seo = SeoArticle::addTag('onel')->addTag('fany')->render();
	// return $seo = SeoProfile::setGender('male')->render();
	// $article = SeoArticle::setSection( 'Front page' )
	// ->setModifiedTime( new DateTime( 'now', new DateTimeZone( 'America/Los_Angeles' ) ) )
	// ->setExpirationTime( '2011-12-31T23:59:59+00:00' )
	// ->setPublishedTime( '2011-11-03T01:23:45Z' )
	// ->addTag( 'weather' )
	// ->addTag( 'football' )
	// ->addAuthor( 'http://example.com/author.html' )
	// ->render();
	// // ->render();
	// return $article;
	// $image = SeoImage::setURL( 'http://example.com/image.jpg' )
	// 		->setSecureURL( 'https://example.com/image.jpg' )
	// 		->setType( 'image/jpeg' )
	// 		->setWidth( 400 )
	// 		->setHeight( 300 )->render();
	// return $image;

	// $video = SeoVideo::setURL( 'http://example.com/video.swf' )
	// ->setSecureURL( 'https://example.com/video.swf' )
	// // ->setType( OpenGraphProtocolVideo::extension_to_media_type() );
	// ->setWidth( 500 )
	// ->setHeight( 400 )->render();
	// return $video;

	$audio = SeoAudio::setURL( 'http://example.com/audio.mp3' )
	->setSecureURL( 'https://example.com/audio.mp3' )
	->setType('audio/mpeg');
	return $audio->render();