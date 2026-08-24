/** Faint dot-grid texture + 2 slow-drifting blurred blobs, layered behind a
 * section's content (`-z-10`). The parent `<section>` needs `relative
 * overflow-hidden`. Purely decorative, so it's `aria-hidden`; motion is
 * automatically frozen by the site's global `prefers-reduced-motion`
 * override in index.css. */
export function SectionBackground() {
  return (
    <div aria-hidden="true">
      <div className="dot-grid -z-10" />
      <div className="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div
          className="bg-blob left-[-10%] top-[5%] h-96 w-96 bg-accent-2/18"
          style={{ animation: "float-blob-a 20s ease-in-out infinite", filter: "blur(70px)" }}
        />
        <div
          className="bg-blob right-[-8%] top-[40%] h-80 w-80 bg-accent/15"
          style={{ animation: "float-blob-b 26s ease-in-out infinite", filter: "blur(70px)" }}
        />
      </div>
    </div>
  );
}
