<?php
require_once("./src/Album.php");
use PHPUnit\Framework\TestCase;


final Class albumTest extends TestCase{

    public function testGetAlbums(): void
    {
        $albumClass = new Album();
        $albums = $albumClass->getAllAlbums();
        $this->assertSame('1', reset($albums)['albumId']);
        $this->assertSame(347, count($albums));
    }

    public function testGetGetAlbumById(): void
    {
        $albumClass = new Album();
        $album = $albumClass->getAlbum(10000);
        $this->assertSame('1', $album);
    }
}

?>