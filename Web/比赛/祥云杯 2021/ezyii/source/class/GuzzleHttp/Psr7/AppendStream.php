<?php


namespace GuzzleHttp\Psr7;


class AppendStream
{
    private $streams = [];
    private $seekable = true;
    public function __toString()
    {
        $this->rewind();
        return "hahaha";
    }
    public function rewind()
    {
        $this->seek(0);
    }
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->seekable) {
            throw new \RuntimeException('This AppendStream is not seekable');
        } elseif ($whence !== SEEK_SET) {
            throw new \RuntimeException('The AppendStream can only seek with SEEK_SET');
        }

        $this->pos = $this->current = 0;

        // Rewind each stream
        foreach ($this->streams as $i => $stream) {
            try {
                $stream->rewind();
            } catch (\Exception $e) {
                throw new \RuntimeException('Unable to seek stream '
                    . $i . ' of the AppendStream', 0, $e);
            }
        }
    }
}