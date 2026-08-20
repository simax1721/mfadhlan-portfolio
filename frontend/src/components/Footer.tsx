import type { Profile } from "../lib/types";
import { useLocale } from "../i18n/useLocale";

export function Footer({ profile }: { profile: Profile }) {
  const { t } = useLocale();

  return (
    <footer className="border-t border-border px-6 py-8">
      <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 text-sm text-text-dim sm:flex-row">
        <p>
          &copy; {new Date().getFullYear()} {profile.name}. {t("footer.builtWith")}
        </p>
        <div className="flex items-center gap-4">
          {profile.github_url && (
            <a
              href={profile.github_url}
              target="_blank"
              rel="noreferrer"
              className="transition-colors hover:text-accent"
            >
              {profile.github_url.replace("https://", "")}
            </a>
          )}
          {profile.linkedin_url && (
            <a
              href={profile.linkedin_url}
              target="_blank"
              rel="noreferrer"
              className="transition-colors hover:text-accent"
            >
              LinkedIn
            </a>
          )}
        </div>
      </div>
    </footer>
  );
}
