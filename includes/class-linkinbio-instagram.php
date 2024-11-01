<?php

class LinkInBio_Instagram {

    /**
     * Default items to fetch from instagram
     */
    const DEFAULT_MEDIA_LIMIT = 50;

    /**
     * LinkInBio_Instagram constructor.
     */
    public function __construct()
    {

    }

    public static function getAvatarUrl( $username=null ){

        if( $username == null ){
            $username = get_option('linkinbio_username', 'yame.be');
        }

        $instagram = new \InstagramScraper\Instagram();

        $avatar = $instagram->getAccount($username);

        return $avatar->getProfilePicUrl();

    }

    /**
     * @param $username
     * @return \InstagramScraper\Model\Media[]
     * @throws \InstagramScraper\Exception\InstagramException
     * @throws \InstagramScraper\Exception\InstagramNotFoundException
     */
    public static function getMedias( $username ){

        $instagram = new \InstagramScraper\Instagram();

        $medias = $instagram->getMedias($username,self::DEFAULT_MEDIA_LIMIT);

        return $medias;

    }

}