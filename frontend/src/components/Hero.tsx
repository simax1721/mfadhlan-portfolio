import type { Profile } from "../lib/types";
import { useLocale } from "../i18n/useLocale";
import { api } from "../lib/api";
import { GitHubIcon, LinkedInIcon } from "./icons";
import { TerminalWindow } from "./TerminalWindow";
import { useTypewriter, sliceSegments, type Segment } from "../hooks/useTypewriter";

// Each entry is one terminal line, split into styled segments. Typed out
// character-by-character (see useTypewriter), left to right across segment
// boundaries, so syntax coloring survives the animation.
const TERMINAL_LINES: Segment[][] = [
  [
    { text: "$ ", className: "text-accent" },
    { text: "php artisan serve", className: "text-text" },
  ],
  [
    { text: "  INFO", className: "text-emerald-400" },
    { text: " Server running on ", className: "text-text-dim" },
    { text: "[http://127.0.0.1:8000]", className: "text-accent-2" },
    { text: ".", className: "text-text-dim" },
  ],
  [],
  [
    { text: "$ ", className: "text-accent" },
    { text: "curl -s localhost:8000/api/bootstrap | jq", className: "text-text" },
  ],
  [{ text: "{", className: "text-text-dim" }],
  [
    {
      text: '  "profile": { "role": "Backend / Fullstack Developer" },',
      className: "text-text-dim",
    },
  ],
  [
    {
      text: '  "skills": ["Laravel", "REST API", "Midtrans", "…"],',
      className: "text-text-dim",
    },
  ],
  [{ text: '  "cache": "hit"', className: "text-text-dim" }],
  [{ text: "}", className: "text-text-dim" }],
];

/** Purely decorative — illustrates "backend developer" visually, isn't
 * live data, so it's hidden from the accessibility tree. */
function TerminalMockup() {
  const { lineIndex, charIndex, done } = useTypewriter(TERMINAL_LINES);

  return (
    <div aria-hidden="true" className="reveal hidden lg:block">
      <TerminalWindow label="backend — zsh">
        {TERMINAL_LINES.map((segments, i) => {
          const visible =
            i < lineIndex
              ? segments
              : i === lineIndex
                ? sliceSegments(segments, charIndex)
                : [];
          return (
            <p key={i} className="min-h-[1.625em]">
              {visible.map((seg, j) => (
                <span key={j} className={seg.className}>
                  {seg.text}
                </span>
              ))}
              {i === lineIndex && !done && (
                <span className="text-accent motion-safe:animate-pulse">▍</span>
              )}
            </p>
          );
        })}
        {done && (
          <p className="pt-1">
            <span className="text-accent">$ </span>
            <span className="text-accent motion-safe:animate-pulse">▍</span>
          </p>
        )}
      </TerminalWindow>
    </div>
  );
}

export function Hero({ profile }: { profile: Profile }) {
  const { t, locale } = useLocale();

  return (
    <section
      id="top"
      className="relative isolate overflow-hidden px-6 pb-24 pt-20 md:pt-32"
    >
      <div className="dot-grid -z-10" aria-hidden="true" />
      <div
        className="pointer-events-none absolute inset-0 -z-10"
        style={{
          background:
            "radial-gradient(600px circle at 50% 0%, color-mix(in srgb, var(--color-accent-2) 15%, transparent), transparent 70%)," +
            "radial-gradient(420px circle at 85% 15%, color-mix(in srgb, var(--color-accent) 12%, transparent), transparent 70%)",
        }}
      />

      <div className="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
        <div className="text-center lg:text-left">
          <p className="reveal mb-4 inline-flex items-center gap-2 rounded-full border border-border bg-surface px-4 py-1.5 text-sm text-text-dim">
            <span className="h-2 w-2 animate-pulse rounded-full bg-accent" />
            {t("hero.openToRemote", { location: profile.location })}
          </p>

          <h1 className="reveal text-4xl font-bold tracking-tight text-heading sm:text-5xl md:text-6xl">
            {t("hero.greeting")}{" "}
            <span className="text-gradient">{profile.name}</span>
          </h1>

          <h2 className="reveal mt-3 font-mono text-lg text-accent sm:text-xl">
            {profile.role}
          </h2>

          <p className="reveal mx-auto mt-6 max-w-2xl text-balance text-base leading-relaxed text-text-dim sm:text-lg lg:mx-0">
            {profile.tagline}
          </p>

          {profile.highlights.length > 0 && (
            <ul className="reveal mx-auto mt-5 flex max-w-2xl flex-wrap items-center justify-center gap-x-6 gap-y-2 font-mono text-xs text-text-dim sm:text-sm lg:mx-0 lg:justify-start">
              {profile.highlights.map((item) => (
                <li key={item} className="flex items-center gap-2">
                  <span className="h-1 w-1 rounded-full bg-accent" />
                  {item}
                </li>
              ))}
            </ul>
          )}

          <div className="reveal mt-10 flex flex-wrap items-center justify-center gap-4 lg:justify-start">
            <a href="#projects" className="btn-primary">
              {t("hero.viewProjects")}
            </a>
            <a href={api.cvUrl(locale)} className="btn-secondary">
              {t("hero.downloadCV")}
            </a>
          </div>

          {(profile.github_url || profile.linkedin_url) && (
            <div className="reveal mt-4 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-sm lg:justify-start">
              {profile.github_url && (
                <a
                  href={profile.github_url}
                  target="_blank"
                  rel="noreferrer"
                  className="inline-flex items-center gap-2 text-text-dim transition-colors hover:text-accent"
                >
                  <GitHubIcon />
                  {t("hero.github")}
                </a>
              )}
              {profile.linkedin_url && (
                <a
                  href={profile.linkedin_url}
                  target="_blank"
                  rel="noreferrer"
                  className="inline-flex items-center gap-2 text-text-dim transition-colors hover:text-accent"
                >
                  <LinkedInIcon />
                  {t("hero.linkedin")}
                </a>
              )}
            </div>
          )}
        </div>

        <TerminalMockup />
      </div>
    </section>
  );
}
