<?php
require_once("./src/Artist.php");
use PHPUnit\Framework\TestCase;


final Class artistTest extends TestCase{

    public function testGetAllArtists(): void
    {
        $artistClass = new Artist();
        $artists = $artistClass->getAllArtists();
        $this->assertSame('1', reset($artists)['artistId']);
        $this->assertSame(277, count($artists));
    }

    public function testGetArtistById(): void
    {
        $artistClass = new Artist();
        $artist = $artistClass->getArtist(10000);
        $this->assertSame(null, $artist);
    }
}

?>