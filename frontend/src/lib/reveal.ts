import type { CSSProperties } from "react";

/** Inline style for staggering `.reveal` elements in a grid/list — each
 * successive item fades in slightly after the previous one instead of the
 * whole group appearing at once. `.reveal`'s own transition duration/easing
 * (index.css) is untouched; this only adds a delay. */
export function revealDelay(index: number, stepMs = 70): CSSProperties {
  return { transitionDelay: `${index * stepMs}ms` };
}
