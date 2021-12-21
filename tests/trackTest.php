<?php
require_once("./src/Track.php");
use PHPUnit\Framework\TestCase;


final Class trackTest extends TestCase{

    public function testGetTracks(): void
    {
        $trackClass = new Track();
        $tracks = $trackClass->getAllTracks();
        $this->assertSame('1', reset($tracks['results'])['trackId']);
        $this->assertSame(2, count($tracks));
    }

    public function testGetTrackById(): void
    {
        $trackClass = new Track();
        $track = $trackClass->getTrack(10000000);
        $this->assertSame(null, $track);
    }
}

?>