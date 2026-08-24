/** Faint dot-grid texture + 2 slow-drifting blurred blobs, layered behind a
 * section's content (`-z-10`). The parent `<section>` needs `relative
 * isolate overflow-hidden` — `isolate` is required, not cosmetic: without a
 * real stacking context on the section, these `-z-10` children compare
 * against the document root instead of staying local, and silently paint
 * behind the page's own root background (invisible) — see
 * `.ai/rules/frontend-styling.md`. Purely decorative, so `aria-hidden`;
 * motion is frozen by the site's global `prefers-reduced-motion` override
 * in index.css.
 *
 * One fixed look, no per-section variants — an earlier pass alternated the
 * dot-grid and blob positions per section (top/bottom, flipped
 * independently of each other) to avoid two textured sections butting up
 * against each other with a hard seam. That solved the seam but created 6
 * different background configurations across 7 sections, which read as
 * inconsistent rather than smooth. Simplified instead: only every other
 * section is textured at all (Hero, Projects, Experience — see each
 * component), so no two textured sections are ever adjacent and the seam
 * problem doesn't arise in the first place.
 *
 * The 2 blobs are offset by a fixed px amount, not `%` — `%` scales with
 * section width while the blob's own diameter doesn't, so on wide
 * viewports the offset was too small relative to the blob's fixed size:
 * most of its solid, still-fully-opaque body ended up sitting past the
 * section edge instead of the blurred fade band, so `overflow-hidden`
 * clipped it while still solid — a hard, visible wall of color instead of
 * a soft glow. A blob needs its center within roughly
 * `radius ± blur radius` of the edge for the visible sliver to actually be
 * inside the blurred falloff; these px offsets keep that true at every
 * viewport width. */
export function SectionBackground() {
  return (
    <div aria-hidden="true">
      <div className="dot-grid -z-10" />
      <div className="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div
          className="bg-blob left-[-40px] top-[18%] h-96 w-96 bg-accent-2/18"
          style={{ animation: "float-blob-a 20s ease-in-out infinite", filter: "blur(70px)" }}
        />
        <div
          className="bg-blob right-[-24px] top-[40%] h-80 w-80 bg-accent/15"
          style={{ animation: "float-blob-b 26s ease-in-out infinite", filter: "blur(70px)" }}
        />
      </div>
    </div>
  );
}
