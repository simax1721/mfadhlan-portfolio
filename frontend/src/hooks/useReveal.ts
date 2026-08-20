import { useEffect, useRef } from "react";

/** Adds `.is-visible` to elements with the `reveal` class as they scroll into view. */
export function useReveal<T extends HTMLElement>(deps: unknown[] = []) {
  const containerRef = useRef<T | null>(null);

  useEffect(() => {
    const root = containerRef.current ?? document;
    const els = root.querySelectorAll(".reveal");
    if (!els.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 },
    );

    els.forEach((el) => observer.observe(el));
    return () => observer.disconnect();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, deps);

  return containerRef;
}
