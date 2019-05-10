<?php declare(strict_types=1);

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace cristianoc72\codegen\generator\utils;

/**
 * A writer implementation.
 *
 * This may be used to simplify writing well-formatted code.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Cristiano Cinotti <cristianocinotti@gmail.com>
 */
class Writer
{
    /** PSR-2 4 spaces indentation */
    const INDENTATION = '    ';

    private $content = '';
    private $indentationLevel = 0;

    public function indent(): self
    {
        $this->indentationLevel += 1;

        return $this;
    }

    public function outdent(): self
    {
        $this->indentationLevel = max($this->indentationLevel - 1, 0);

        return $this;
    }

    /**
     * @param string $content
     *
     * @return Writer
     */
    public function writeln(string $content = ''): self
    {
        $this->write($content . "\n");

        return $this;
    }

    /**
     * @param string $content
     *
     * @return Writer
     */
    public function write(string $content): self
    {
        $lines = explode("\n", $content);
        for ($i = 0, $c = count($lines); $i < $c; $i++) {
            if ($this->indentationLevel > 0
                    && !empty($lines[$i])
                    && (empty($this->content) || "\n" === substr($this->content, -1))) {
                $this->content .= str_repeat(self::INDENTATION, $this->indentationLevel);
            }

            $this->content .= $lines[$i];

            if ($i + 1 < $c) {
                $this->content .= "\n";
            }
        }

        return $this;
    }

    public function rtrim(): self
    {
        $addNl = "\n" === substr($this->content, -1);
        $this->content = rtrim($this->content);

        if ($addNl) {
            $this->content .= "\n";
        }

        return $this;
    }

    /**
     * @param string $search
     *
     * @return bool
     */
    public function endsWith(string $search): bool
    {
        return substr($this->content, -strlen($search)) === $search;
    }

    public function reset(): self
    {
        $this->content = '';
        $this->indentationLevel = 0;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
