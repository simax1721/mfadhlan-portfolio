import { useLocale } from "../i18n/useLocale";

export function LanguageToggle() {
  const { locale, setLocale } = useLocale();

  return (
    <div
      className="flex items-center rounded-full border border-border bg-surface p-0.5 font-mono text-xs"
      role="group"
      aria-label="Language"
    >
      {(["en", "id"] as const).map((code) => (
        <button
          key={code}
          onClick={() => setLocale(code)}
          aria-pressed={locale === code}
          className={`rounded-full px-2.5 py-1 uppercase transition-colors ${
            locale === code
              ? "bg-accent text-bg"
              : "text-text-dim hover:text-heading"
          }`}
        >
          {code}
        </button>
      ))}
    </div>
  );
}
