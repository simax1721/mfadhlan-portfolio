import { useLocale } from "../i18n/useLocale";
import { TerminalWindow } from "./TerminalWindow";
import { useTypewriter, sliceSegments, type Segment } from "../hooks/useTypewriter";

const LOADER_LINES: Segment[][] = [
  [
    { text: "$ ", className: "text-accent" },
    { text: "curl -s /api/bootstrap", className: "text-text" },
  ],
];

export function FullPageLoader() {
  const { t } = useLocale();
  const { lineIndex, charIndex, done } = useTypewriter(LOADER_LINES);
  const visible =
    lineIndex > 0 ? LOADER_LINES[0] : sliceSegments(LOADER_LINES[0], charIndex);

  return (
    <div className="relative isolate flex min-h-screen items-center justify-center overflow-hidden bg-bg px-6">
      <div className="dot-grid -z-10" aria-hidden="true" />
      <div
        className="pointer-events-none absolute inset-0 -z-10"
        style={{
          background:
            "radial-gradient(500px circle at 50% 50%, color-mix(in srgb, var(--color-accent-2) 12%, transparent), transparent 70%)",
        }}
      />

      <TerminalWindow label="portfolio — zsh" className="w-full max-w-sm">
        <p>
          {visible.map((seg, i) => (
            <span key={i} className={seg.className}>
              {seg.text}
            </span>
          ))}
          {!done && <span className="text-accent motion-safe:animate-pulse">▍</span>}
        </p>
        {done && (
          <p className="mt-2 text-text-dim">
            {t("status.loading")}{" "}
            <span className="text-accent motion-safe:animate-pulse">▍</span>
          </p>
        )}
      </TerminalWindow>
    </div>
  );
}

export function ErrorScreen({ message }: { message: string }) {
  const { t } = useLocale();

  return (
    <div className="flex min-h-screen flex-col items-center justify-center gap-3 bg-bg px-6 text-center">
      <p className="text-lg font-semibold text-heading">
        {t("status.errorTitle")}
      </p>
      <p className="max-w-md text-sm text-text-dim">{message}</p>
      <p className="text-xs text-text-dim">{t("status.errorHint")}</p>
    </div>
  );
}
