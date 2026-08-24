import { useEffect, useState } from "react";
import type { Profile } from "../lib/types";
import { useLocale } from "../i18n/useLocale";
import { LanguageToggle } from "./LanguageToggle";
import { ThemeToggle } from "./ThemeToggle";

/** Pulls the handle out of a GitHub URL, e.g. "https://github.com/simax1721" -> "simax1721". */
function githubHandle(githubUrl: string | null): string | null {
  if (!githubUrl) return null;
  const match = githubUrl.match(/github\.com\/([^/?#]+)/i);
  return match?.[1] ?? null;
}

const SECTION_IDS = [
  "about",
  "projects",
  "skills",
  "experience",
  "education",
  "contact",
];

// Matches the mobile menu's `duration-300` collapse transition — the
// scroll target is only computed correctly once the menu has fully
// closed, otherwise the page height changes mid-scroll and the anchor
// lands in the wrong place.
const MOBILE_MENU_CLOSE_MS = 320;

export function Navbar({ profile }: { profile: Profile }) {
  const { t } = useLocale();
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [activeId, setActiveId] = useState<string | null>(null);
  const handle = githubHandle(profile.github_url) ?? profile.name;

  // Close the mobile menu first, then scroll — scrolling while the menu is
  // still collapsing races the browser's own anchor-scroll against the
  // shrinking page height and overshoots the target.
  const goToSection = (e: React.MouseEvent<HTMLAnchorElement>, id: string) => {
    e.preventDefault();
    setOpen(false);
    window.setTimeout(() => {
      document.getElementById(id)?.scrollIntoView({ behavior: "smooth", block: "start" });
    }, MOBILE_MENU_CLOSE_MS);
  };

  const LINKS = [
    { href: "#about", id: "about", label: t("nav.about") },
    { href: "#projects", id: "projects", label: t("nav.projects") },
    { href: "#skills", id: "skills", label: t("nav.skills") },
    { href: "#experience", id: "experience", label: t("nav.experience") },
    { href: "#education", id: "education", label: t("nav.education") },
    { href: "#contact", id: "contact", label: t("nav.contact") },
  ];

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8);
    onScroll();
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  // Close the mobile menu on Escape, from anywhere on the page.
  useEffect(() => {
    if (!open) return;
    const onKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") setOpen(false);
    };
    window.addEventListener("keydown", onKeyDown);
    return () => window.removeEventListener("keydown", onKeyDown);
  }, [open]);

  // Scroll-spy: highlight whichever section is currently most in view.
  useEffect(() => {
    const sections = SECTION_IDS.map((id) => document.getElementById(id)).filter(
      (el): el is HTMLElement => el !== null,
    );
    if (sections.length === 0) return;

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((e) => e.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
        if (visible) setActiveId(visible.target.id);
      },
      { rootMargin: "-40% 0px -50% 0px", threshold: [0, 0.25, 0.5, 0.75, 1] },
    );

    sections.forEach((el) => observer.observe(el));
    return () => observer.disconnect();
  }, []);

  return (
    <header
      className={`sticky top-0 z-50 border-b transition-colors ${
        scrolled
          ? "border-border bg-bg/80 backdrop-blur-md"
          : "border-transparent bg-transparent"
      }`}
    >
      <nav className="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <a
          href="#top"
          title={profile.name}
          className="rounded font-mono text-lg font-semibold text-accent"
        >
          {handle}
        </a>

        <ul className="hidden items-center gap-8 md:flex">
          {LINKS.map((link) => (
            <li key={link.href}>
              <a
                href={link.href}
                aria-current={activeId === link.id ? "true" : undefined}
                className={`rounded text-sm transition-colors hover:text-accent ${
                  activeId === link.id ? "text-accent" : "text-text-dim"
                }`}
              >
                {link.label}
              </a>
            </li>
          ))}
        </ul>

        <div className="flex items-center gap-3">
          <LanguageToggle />
          <ThemeToggle />
          <a
            href="#contact"
            className="hidden rounded-full border border-accent/40 px-4 py-2 text-sm font-medium text-accent transition-colors hover:bg-accent/10 lg:inline-block"
          >
            {t("nav.letsTalk")}
          </a>

          <button
            className="rounded p-2 text-heading md:hidden"
            onClick={() => setOpen((o) => !o)}
            aria-label="Toggle menu"
            aria-expanded={open}
            aria-controls="mobile-nav-menu"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path
                d="M4 6h16M4 12h16M4 18h16"
                stroke="currentColor"
                strokeWidth="1.75"
                strokeLinecap="round"
              />
            </svg>
          </button>
        </div>
      </nav>

      <ul
        id="mobile-nav-menu"
        inert={!open}
        className={`flex flex-col gap-1 overflow-hidden border-t bg-bg px-6 transition-all duration-300 md:hidden ${
          open
            ? "max-h-96 border-border py-4 opacity-100"
            : "max-h-0 border-transparent py-0 opacity-0"
        }`}
      >
        {LINKS.map((link) => (
          <li key={link.href}>
            <a
              href={link.href}
              onClick={(e) => goToSection(e, link.id)}
              aria-current={activeId === link.id ? "true" : undefined}
              className={`block rounded py-2 text-sm hover:text-accent ${
                activeId === link.id ? "text-accent" : "text-text-dim"
              }`}
            >
              {link.label}
            </a>
          </li>
        ))}
      </ul>
    </header>
  );
}
