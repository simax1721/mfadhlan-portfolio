import type { Profile } from "../lib/types";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";

function toWhatsAppLink(phone: string) {
  const digits = phone.replace(/\D/g, "");
  const normalized = digits.startsWith("0") ? `62${digits.slice(1)}` : digits;
  return `https://wa.me/${normalized}`;
}

export function Contact({ profile }: { profile: Profile }) {
  const { t } = useLocale();

  return (
    <section id="contact" className="bg-band px-6 py-24">
      <div className="mx-auto max-w-3xl">
        <SectionHeading
          eyebrow={t("contact.eyebrow")}
          title={t("contact.title")}
        />

        <div className="reveal rounded-2xl border border-border bg-surface p-8 text-center sm:p-12">
          <p className="mx-auto max-w-lg text-balance text-text-dim">
            {t("contact.blurb")}
          </p>

          <div className="mt-8 flex flex-wrap items-center justify-center gap-4">
            <a href={`mailto:${profile.email}`} className="btn-primary">
              {t("contact.emailMe")}
            </a>
            {profile.phone && (
              <a
                href={toWhatsAppLink(profile.phone)}
                target="_blank"
                rel="noreferrer"
                className="btn-secondary"
              >
                {t("contact.whatsapp")}
              </a>
            )}
            {profile.github_url && (
              <a
                href={profile.github_url}
                target="_blank"
                rel="noreferrer"
                className="btn-secondary"
              >
                {t("contact.github")}
              </a>
            )}
            {profile.linkedin_url && (
              <a
                href={profile.linkedin_url}
                target="_blank"
                rel="noreferrer"
                className="btn-secondary"
              >
                {t("contact.linkedin")}
              </a>
            )}
          </div>

          <div className="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 font-mono text-sm text-text-dim">
            <span>{profile.email}</span>
            {profile.phone && <span>{profile.phone}</span>}
            <span>{profile.location}</span>
          </div>
        </div>
      </div>
    </section>
  );
}
