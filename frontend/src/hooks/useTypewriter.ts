import { useEffect, useState } from "react";

export type Segment = { text: string; className?: string };

export function sliceSegments(segments: Segment[], charCount: number): Segment[] {
  let remaining = charCount;
  const out: Segment[] = [];
  for (const seg of segments) {
    if (remaining <= 0) break;
    const take = Math.min(remaining, seg.text.length);
    out.push({ text: seg.text.slice(0, take), className: seg.className });
    remaining -= take;
  }
  return out;
}

const DEFAULT_MS_PER_CHAR = 22;
const DEFAULT_PAUSE_BETWEEN_LINES = 320;

/** Types `lines` out one character at a time, left to right, with a short
 * pause between lines. Skips straight to the finished state when the user
 * prefers reduced motion. Loops back to the start when `loop` is true
 * (used for open-ended "still working" screens like a loader). */
export function useTypewriter(
  lines: Segment[][],
  options?: { msPerChar?: number; pauseBetweenLines?: number; loop?: boolean },
) {
  const msPerChar = options?.msPerChar ?? DEFAULT_MS_PER_CHAR;
  const pauseBetweenLines = options?.pauseBetweenLines ?? DEFAULT_PAUSE_BETWEEN_LINES;
  const loop = options?.loop ?? false;

  const [lineIndex, setLineIndex] = useState(0);
  const [charIndex, setCharIndex] = useState(0);
  const [done, setDone] = useState(false);

  useEffect(() => {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      setLineIndex(lines.length);
      setDone(true);
      return;
    }

    let cancelled = false;
    let timeoutId: ReturnType<typeof setTimeout>;

    const lineLength = (i: number) =>
      lines[i]?.reduce((sum, seg) => sum + seg.text.length, 0) ?? 0;

    function tick(li: number, ci: number) {
      if (cancelled) return;
      if (li >= lines.length) {
        if (loop) {
          timeoutId = setTimeout(() => {
            setLineIndex(0);
            setCharIndex(0);
            tick(0, 0);
          }, pauseBetweenLines * 2);
          return;
        }
        setDone(true);
        return;
      }
      const len = lineLength(li);
      if (ci >= len) {
        timeoutId = setTimeout(() => {
          setLineIndex(li + 1);
          setCharIndex(0);
          tick(li + 1, 0);
        }, pauseBetweenLines);
        return;
      }
      timeoutId = setTimeout(() => {
        setCharIndex(ci + 1);
        tick(li, ci + 1);
      }, msPerChar);
    }

    tick(0, 0);
    return () => {
      cancelled = true;
      clearTimeout(timeoutId);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  return { lineIndex, charIndex, done };
}
