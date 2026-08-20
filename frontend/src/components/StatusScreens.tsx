import { useLocale } from "../i18n/useLocale";

export function FullPageLoader() {
  const { t } = useLocale();

  return (
    <div className="flex min-h-screen flex-col items-center justify-center gap-4 bg-bg">
      <div className="h-10 w-10 animate-spin rounded-full border-2 border-border border-t-accent" />
      <p className="font-mono text-sm text-text-dim">{t("status.loading")}</p>
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
